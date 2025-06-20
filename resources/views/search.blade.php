<!DOCTYPE html>
<html>
<head>
    <title>🔍 ValueSERP Multi-Search</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            padding: 30px;
        }
        .container {
            max-width: 700px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            background: #28a745;
            border: none;
            padding: 10px 20px;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
        .result {
            background: #f9f9f9;
            margin-top: 20px;
            padding: 15px;
            border-left: 5px solid #007bff;
            border-radius: 6px;
        }
        a {
            color: #007bff;
            font-weight: bold;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .download-link {
            display: inline-block;
            margin-top: 15px;
            background: #007bff;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 6px;
        }
        .download-link:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>🔍 Enter 4-5 Search Queries</h2>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/search">
        @csrf
        @for ($i = 0; $i < 5; $i++)
            <input type="text" name="queries[]" placeholder="Search Query {{ $i+1 }}" value="{{ old('queries.' . $i) }}">
        @endfor
        <button type="submit">Search</button>
    </form>

    @if(isset($results))
        <h3>📋 Search Results</h3>
        <a class="download-link" href="{{ route('export.csv') }}">⬇️ Download CSV</a>

        @foreach($results as $result)
            <div class="result">
                <strong>🔎 {{ $result['query'] }}</strong><br>
                <a href="{{ $result['link'] }}" target="_blank">{{ $result['title'] }}</a><br>
                <p>{{ $result['snippet'] }}</p>
            </div>
        @endforeach
    @endif
</div>
</body>
</html>
