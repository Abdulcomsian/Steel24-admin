<!-- resources/views/admin/lots/import-csv.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>CSV Import and Data</title>
    <style>
        .text-danger{
            color: red;
        }
    </style>
</head>
<body>
    <h1>CSV Import</h1>

    <form action="{{ url('admin/lots/import-csv') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file" accept=".csv">
        @error("csv_file")
            <span class="text-danger"><strong>{{$message}}</strong></span>
        @enderror
        <button type="submit">Import</button>
    </form>

    <hr>

    <h1>CSV Data</h1>
   
    {{-- <table>
        <thead>
            <tr>
                <th>Seller</th>
                <th>Plant</th>
                <th>Material Location</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>

            
            @dd($lotsData)
            @foreach($lotsData as $lot)
            
                <tr>
                    <td>{{ $lot->Seller }}</td>
                    <td>{{ $lot->Plant }}</td>
                    <td>{{ $lot->materialLocation }}</td>
                    <td>{{ $lot->description }}</td>
                    <td>{{ $lot->Quantity }}</td>
                    <td>{{ $lot->StartDate }}</td>
                    <td>{{ $lot->EndDate }}</td>
                    <td>{{ $lot->Price }}</td>
                </tr>
            @endforeach
        </tbody>
    </table> --}}
</body>
</html>



