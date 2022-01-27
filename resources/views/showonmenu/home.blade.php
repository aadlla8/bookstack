@extends('layouts.simple')
@section('head')
    <link href="/css/highchecktree.css" rel="stylesheet" type="text/css" />
@endsection
@section('body')
    <div class="container small">
        <div class="card content-wrap">
            <strong>Show/Hide item on menu.</strong>
            <div id="tree-container"></div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ url('/libs/highchecktree.js') }}" nonce="{{ $cspNonce }}"></script>
    <script type="text/javascript" nonce="{{ $cspNonce }}">
        function callupdate(data, show) {
            let rel = ($(data[0]).attr('rel'));
            fetch('/api/menuitems', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Token bRkuQlKaKnvmXxcw6e5NFyiGTlDtxgxp:6QxpsCdERVsqUyFjpsQH99NTuVJ9ZV2h`,
                },
                body: JSON.stringify({
                    "type": rel.split('_')[0],
                    "id": rel.split('_')[1],
                    "show": show,
                }),
                redirect: 'follow',
                credentials: 'omit',
            }).then(res => res.json()).then(res => console.log("res:", res))
        }
        fetch('/api/menuitems', {
            method: 'GET',
            headers: {
                'Authorization': `Token bRkuQlKaKnvmXxcw6e5NFyiGTlDtxgxp:6QxpsCdERVsqUyFjpsQH99NTuVJ9ZV2h`,
            },
            redirect: 'follow',
            credentials: 'omit',
        }).then((res) => {
            return res.json();
        }).then(res => {
            var mockData = JSON.parse(res)

            $('#tree-container').highCheckTree({
                data: mockData,
                onCheck: function(data) {
                    callupdate(data, 1);
                },
                onUnCheck: function(data) {
                    callupdate(data, 0);
                },
                onHalfCheck: function(data) {
                    callupdate(data, 1);
                },
            });
        })
    </script>
@endsection
