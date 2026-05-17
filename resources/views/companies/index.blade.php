<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Companies (live)</title>
    @vite(['resources/js/app.js'])
    <style>
        body { font-family: system-ui, sans-serif; max-width: 640px; margin: 2rem auto; padding: 0 1rem; }
        form { display: flex; gap: .5rem; margin-bottom: 1rem; }
        input { flex: 1; padding: .5rem; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: .5rem 1rem; background: #2563eb; color: white; border: 0; border-radius: 4px; cursor: pointer; }
        li { padding: .5rem; border-bottom: 1px solid #eee; }
        li.new { background: #fef3c7; transition: background 2s; }
    </style>
</head>
<body>
    <h1>Companies</h1>
    <p><small>Open this page in two tabs and add a company in one — it appears live in both.</small></p>

    <form id="company-form">
        <input name="name" placeholder="Company name" required>
        <input name="industry" placeholder="Industry">
        <button type="submit">Add</button>
    </form>

    <ul id="company-list">
        @foreach ($companies as $company)
            <li data-id="{{ $company->id }}">
                <strong>{{ $company->name }}</strong>
                @if ($company->industry) — {{ $company->industry }} @endif
            </li>
        @endforeach
    </ul>

    <script>
        const list = document.getElementById('company-list');
        const form = document.getElementById('company-form');
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        function prepend(c) {
            if (list.querySelector(`li[data-id="${c.id}"]`)) return;
            const li = document.createElement('li');
            li.dataset.id = c.id;
            li.className = 'new';
            li.innerHTML = `<strong>${c.name}</strong>${c.industry ? ' — ' + c.industry : ''} - ${c.created_at} - ${c.random}`;
            list.prepend(li);
            setTimeout(() => li.classList.remove('new'), 50);
        }

        // Subscribe once Echo is ready (echo.js runs as a module import).
        window.addEventListener('load', () => {
            window.Echo.channel('companies')
                .listen('.App\\Events\\CompanyCreated', (e) => prepend(e))
                // Reverb sends the FQCN with backslashes; the leading "." opts out of Laravel's
                // auto-prefix. Alternatively define broadcastAs() on the event.
                ;
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const data = Object.fromEntries(new FormData(form));
            const res = await fetch('{{ route('companies.store') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: JSON.stringify(data),
            });
            if (res.ok) form.reset();
        });
    </script>
</body>
</html>
