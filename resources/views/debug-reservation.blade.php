@extends('shared')

@section('title', 'Debug Reservation JSON')

@section('content')
<div style="padding: 40px; background: #f5f5f5;">
    <h2>DEBUG: Menu JSON Data</h2>
    
    <div style="background: white; padding: 20px; border-radius: 5px; margin-top: 20px;">
        <h4>Test JSON encoding:</h4>
        <pre id="json-output" style="background: #f9f9f9; padding: 15px; border-radius: 4px; overflow-x: auto;">Waiting for JS...</pre>
    </div>
</div>

<script>
    // Test @json() directly
    const testMenus = @json($menus);
    
    console.log('testMenus length:', testMenus.length);
    console.log('First menu:', testMenus[0]);
    
    // Display in HTML
    document.getElementById('json-output').innerText = JSON.stringify(testMenus.slice(0, 3), null, 2);
</script>
@endsection
