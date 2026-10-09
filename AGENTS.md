# Agent Workflow Rules

1. **Context Check**: Sebelum mengubah file besar, selalu verifikasi relasi modul menggunakan `graphify query "<topik>"`.
2. **GSD Protocol**: Pecah task ke dalam format: [Plan] -> [Execute] -> [Verify]. Selesaikan satu per satu.
3. **Pre-Commit Review**: Kumpulkan perubahan dalam batch besar terlebih dahulu, lalu verifikasi diff menggunakan `coderabbit review --uncommitted` sebelum finalisasi.
