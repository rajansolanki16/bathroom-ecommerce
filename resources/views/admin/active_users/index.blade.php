<x-admin.header :title="'Active Users'" />
<div class="container">
    <h1>Active Users (last 2 hours)</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Last Active</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activeUsers as $active)
                <tr>
                    <td>{{ $active->user->id ?? '-' }}</td>
                    <td>{{ $active->user->name ?? '-' }}</td>
                    <td>{{ $active->user->email ?? '-' }}</td>
                    <td>{{ $active->last_active_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<x-admin.footer />