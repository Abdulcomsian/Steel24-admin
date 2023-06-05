// 
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.1.1/firebase-app.js";
import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/9.1.1/firebase-database.js";

// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries


// import { initializeApp } from 'firebase/app';
// import { getDatabase, ref, onValue } from "firebase/database";
console.log(lotid)


const firebaseConfig = {
  apiKey: "AIzaSyCTMa2MX27L40POGDkoA-J_rOFL2bT7jz4",
  authDomain: "steel24-a898f.firebaseapp.com",
  databaseURL: "https://steel24-a898f-default-rtdb.firebaseio.com",
  projectId: "steel24-a898f",
  storageBucket: "steel24-a898f.appspot.com",
  messagingSenderId: "924896686955",
  appId: "1:924896686955:web:e7b4c62ab4e416a61f522e",
  measurementId: "G-ZPC54XMY2V"
};


const dataApp = initializeApp(firebaseConfig);
console.log('sadfsf', dataApp)

const db = getDatabase(dataApp);

const candidateData = ref(db, '/TodaysLots/liveList/' + lotid);

console.log(candidateData)

onValue(candidateData, (snapshot) => {
  const data = snapshot.val();
  var lastbidid = $('#msg').children('tr:first').children('th').text() || 0;

  let tbodydata = ''

  if (lastbidid < data.lastBid.id) {
    tbodydata = ''
    tbodydata += '<tr>';
    tbodydata += '<th scope="row">' + data.lastBid.id + '</th>';
    tbodydata += '<td>' + data.lastBid.customerName + '</td>';
    tbodydata += '<td>' + data.lastBid.amount + '</td>';
    tbodydata += '<td>' + data.lastBid.created_at + '</td>';
    tbodydata += '<td><a href="/admin/customers/' + data.lastBid.customerId + '" class="btn btn-primary btn-sm"> Customer Details</a></td >';
    tbodydata += '</tr>';

    $('table > tbody > tr:first').before(tbodydata);
  }
  // document.getElementById('datatbody').innerHTML = 
});
