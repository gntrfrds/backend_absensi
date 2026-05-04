<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Absensi</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 20px;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .left {
            text-align: left;
        }

        .status-hadir {
            color: green;
            font-weight: bold;
        }

        .status-izin {
            color: orange;
            font-weight: bold;
        }

        .status-sakit {
            color: blue;
            font-weight: bold;
        }

        .status-tanpa {
            color: red;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }
    </style>
</head>

<body>

<h2>DATA ABSENSI</h2>
<div class="subtitle">
    Dicetak pada: {{ date('d-m-Y H:i') }}
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Role</th>
            <th>Tanggal</th>
            <th>Masuk</th>
            <th>Pulang</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $index => $row)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td class="left">{{ $row->name }}</td>
            <td>{{ $row->role }}</td>
            <td>{{ $row->date }}</td>

            <td>
                {{ $row->check_in ? date('H:i', strtotime($row->check_in)) : '-' }}
            </td>

            <td>
                {{ $row->check_out ? date('H:i', strtotime($row->check_out)) : '-' }}
            </td>

            <td>
                @php
                    $status = 'Tanpa Keterangan';

                    if ($row->check_in) {
                        $status = 'Hadir';
                    }
                @endphp

                @if($status == 'Hadir')
                    <span class="status-hadir">Hadir</span>
                @else
                    <span class="status-tanpa">Tanpa</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    <p>Admin Absensi</p>
    <br><br>
    <p>(__________________)</p>
</div>

</body>
</html>
