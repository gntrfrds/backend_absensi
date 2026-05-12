<!DOCTYPE html>
<html>

<head>

    <title>Rekap Absensi</title>

    <style>

        body {
            font-family: sans-serif;
        }

        table {

            width: 100%;

            border-collapse: collapse;

            margin-top: 20px;
        }

        table,
        th,
        td {

            border: 1px solid black;
        }

        th,
        td {

            padding: 8px;

            text-align: left;
        }

        th {

            background: #f0f0f0;
        }

    </style>

</head>

<body>

    <h2>Rekap Absensi</h2>

    <table>

        <thead>

            <tr>

                <th>No</th>

                <th>Nama</th>

                <th>Role</th>

                <th>Tanggal</th>

                <th>Check In</th>

                <th>Check Out</th>

                <th>Status</th>

            </tr>

        </thead>

        <tbody>

            @foreach($data as $item)

            <tr>

                <td>{{ $loop->iteration }}</td>

                <td>{{ $item->name }}</td>

                <td>{{ $item->role }}</td>

                <td>{{ $item->date }}</td>

                <td>{{ $item->check_in }}</td>

                <td>{{ $item->check_out }}</td>

                <td>

                    @if($item->check_in_status == 'late')

                        Telat

                    @elseif($item->check_out_status == 'early_leave')

                        Pulang Cepat

                    @else

                        Hadir

                    @endif

                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

</body>
</html>