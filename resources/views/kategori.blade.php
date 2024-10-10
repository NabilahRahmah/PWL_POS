<!DOCTYPE html>
<html>
    <head>
        <title>Data Kategori Barang</title>
    </head>
    <body>
        <h1>Data Kategori Barang</h1>
        <table border="1" cellpadding="2" cellspacing="0">
            <tr>
                <th>ID</th>
                <th>Kode Kategori</th>
                <th>NAma Kategori</th>
            </tr>        
            @foreach ($data as $d)
            <tr>
                <td>{{ $d->kategori_id }} </d>  
                <td>{{ $d->kategori_kode }} </d>  
                <td>{{ $d->kategori_nama }} </d>    
            </tr>                
            @endforeach
        </table>
    </body>
</html>