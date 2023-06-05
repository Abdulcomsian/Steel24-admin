var dotenv = require('dotenv');

dotenv.config({
    path: ".env"
});

var ev = require('events');

ev.EventEmitter.defaultMaxListeners = Infinity;

var mysql = require('mysql2');

var con = {
    host: process.env.DB_HOST,
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_NAME,
    connectionLimit: 10
};

const pool = mysql.createPool(con);

const promisePool = pool.promise();


var query_logs = false;

var Query = function () {};
Query.prototype.row = async function (sql, param) {


    try {
        if (query_logs) {
            console.log(sql.green, param);
        }
        var data = await promisePool.query(sql, param);

        return data[0][0];
    } catch (e) {
        console.log(e);

        console.log(e.code.red);
        console.log(e.message.red);
        console.log(sql.green, param);
    }
}
Query.prototype.result = async function (sql, param) {
    try {
        if (query_logs) {
            console.log(sql.green, param);
        }
        var data = await promisePool.query(sql, param);
        return data[0];
    } catch (e) {
        console.log(e.code.red);
        console.log(e.message.red);
        console.log(sql.green, param);
    }
}
Query.prototype.insert = async function (table, data) {
    var sql = "insert into " + table + " set ";
    var set = [];
    for (var k in data) {
        set.push(k + "='" + data[k] + "'");
    }
    sql += set.join(", ");
    try {
        if (query_logs) {
            console.log(sql.green);
        }
        var data = await promisePool.query(sql);
        return data[0];
    } catch (e) {
        console.log(e);

        console.log(e.code.red);
        console.log(e.message.red);
        console.log(sql.green);
    }
};
Query.prototype.update = async function (sql, param) {
    try {
        if (query_logs) {
            console.log(sql.green, param);
        }
        var data = await promisePool.query(sql, param);
        return data;
        //return data[0][0];
    } catch (e) {
        console.log(e.code.red);
        console.log(e.message.red);
        console.log(sql.green, param);
    }
}

Query.prototype.delete = async function (sql, param) {
    try {
        if (query_logs) {
            console.log(sql.green, param);
        }
        var data = await promisePool.query(sql, param);
        return data;
        //return data[0][0];
    } catch (e) {
        console.log(e.code.red);
        console.log(e.message.red);
        console.log(sql.green, param);
    }
}

var db = new Query();
const express = require('express');
const app = express();
const server = require('http').createServer(app);
const port = process.env.PORT || 3000;
// var server = app.listen(3000);
// server.listen(port);
console.log('hello word');


console.log("server listening at port", port);

var io = require('socket.io')(3000);

var clients = {}

function check_auth(socket, user) {
    var user_id = socket.handshake.query.user_id;
    var token = '';
    if(socket.handshake.query.token){
        var token = socket.handshake.query.token;
        console.log("Token found ===========", token);
    }
    
    if (!user) {
        console.log("user not found", user_id);

        socket.disconnect();
        return false;
    } else {
        if(token){
            console.log(user.token);
            if(token == user.token){
                console.log("user connected", user_id);
                return true;
            }else{
                console.log("User not found", user_id);
               
                socket.disconnect();
                return false;
            }

        }else{
            console.log("user connected", user_id);
            return true;
        }
    }
}

io.on('connection', async function (socket) {

    var user_id = socket.handshake.query.user_id;
   
    var sql = "select id from users where id=?";
    var user = await db.row(sql, [user_id]);
    var authenticated = check_auth(socket, user);
    if (!authenticated) {
        return;
    }

    if (!clients[user_id]) {
        clients[user_id] = [];
    }
    clients[user_id].push(socket.id); 

    socket.on("user_connected", function(user_id) {
        console.log("user connected " + socket.id);
    });
    
    socket.on('disconnect', function () {
        console.log(user_id, "is Disconnected");        
        
        var i = clients[user_id].indexOf(socket.id);
        clients[user_id].splice([i], 1);
    });

  

    socket.on("submit_bids", async function (data, ack) {

        console.log("message data :: ", data);
        var to_user_id = data.to_user_id;
        data.from_user_id = user_id;
        
        data.created_at = new Date().toISOString().
                replace(/T/, ' ').// replace T with a space
                replace(/\..+/, '')     // delete the dot and everything after

        var message = data;
        message.to_user_id = to_user_id;
        if (typeof data.message !== 'undefined') {
            temp_m = data.message;
            message.message = temp_m.replace(/'/g, "\\'");
        }

        // console.log("insert chat data :: ", message);
        var res = await db.insert("chat", message);
        var _message = await get_message_by_id(res.insertId.toString(), user_id);
      
        _clients = clients[_message.to_user_id];

        if (_clients) {
            for (var k in _clients) {
                _message.m_type = "receive";
                console.log('call listener =====',_message);
                io.to(_clients[k]).emit('incoming_message', _message);
            }
        }

        _message.m_type = "sent";
        if (ack) {
            ack(_message);
        }


    });

});

async function get_message_by_id(id, user_id) {
    var sql = "select * from chat where id=?";
    var message = await db.row(sql, [id]);

    if (message.from_user_id == user_id) {
        message.m_type = 'sent';
    } else {
        message.m_type = 'receive';
    }

    return message;
}