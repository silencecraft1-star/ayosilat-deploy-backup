# Agent Workflow Rules

1. **Context Check**: Sebelum mengubah file besar, selalu verifikasi relasi modul menggunakan `graphify query "<topik>"`.
2. **GSD Protocol**: Pecah task ke dalam format: [Plan] -> [Execute] -> [Verify]. Selesaikan satu per satu.
3. **CodeRabbit Standard Check**: Setiap menulis, memodifikasi, atau mereview kode, terapkan standar evaluasi CodeRabbit secara ketat:
   - **Security**: Sanitasi input, SQL Injection, XSS, CSRF, Authorization/Policy, dan proteksi credential/data sensitif.
   - **Logic & Edge Cases**: Null safety/undefined properties, boundary conditions, race conditions, dan pencegahan regresi.
   - **Performance**: Eliminasi N+1 queries (terutama Laravel Eloquent), larangan query database langsung di dalam Blade view/loop, serta optimasi memori.
   - **Architecture & Standards**: Kepatuhan konvensi Laravel (MVC murni, Service/Repository pattern bila perlu), SOLID, DRY, dan zero dead code.
   - **Review Output**: Sajikan temuan dengan kategori jelas (🚨 Critical, ⚠️ Major, 💡 Suggestion) lengkap dengan solusi/diff konkrit yang actionable.
