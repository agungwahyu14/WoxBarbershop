<?php

return [
    // Pemesanan Messages
    'booking_success_title' => 'Pemesanan Berhasil!',
    'booking_success_name' => 'Nama',
    'booking_success_queue' => 'Nomor Pesanan',
    'booking_success_time' => 'Waktu',
    'booking_success_service' => 'Layanan',
    'booking_success_note' => 'Mohon hadir 10 menit sebelum waktu booking Anda',

    // Headers and Titles
    'booking_history_title' => 'Riwayat Pemesanan',
    'booking_history_subtitle' => 'Lihat dan kelola riwayat janji temu Anda.',
    'booking_detail_title' => 'Rincian Pemesanan',
    'booking_detail_subtitle' => 'Lihat dan kelola rincian pemesanan Anda.',
    'edit_booking' => 'Edit Pemesanan',
    'edit_booking_subtitle' => 'Ubah rincian pemesanan Anda sesuai kebutuhan.',

    // Basic Information
    'booking_number' => 'Pemesanan #',
    'customer' => 'Pelanggan',
    'customer_name' => 'Nama Pelanggan',
    'booking_date' => 'Tanggal Pemesanan',
    'date_time' => 'Tanggal & Waktu',
    'status' => 'Status',
    'payment' => 'Pembayaran',
    'queue_number' => 'Nomor Pesanan',
    'total' => 'Total',
    'notes' => 'Catatan',
    'hairstyle' => 'Gaya Rambut',
    'service' => 'Layanan',
    'service_details' => 'Rincian Layanan',

    // Status Labels
    'status_pending' => 'Menunggu',
    'status_confirmed' => 'Dikonfirmasi',
    'status_in_progress' => 'Sedang Diproses',
    'status_completed' => 'Selesai',
    'status_cancelled' => 'Dibatalkan',

    // Payment Status
    'payment_status' => 'Status Pembayaran',
    'payment_status_paid' => 'Sudah Bayar',
    'payment_status_unpaid' => 'Belum Bayar',
    'payment_status_pending' => 'Menunggu',
    'payment_status_settlement' => 'Selesai',
        // Payment Methods
    'payment_method_cash' => 'Tunai',
    'payment_method_bank' => 'Transfer Bank',

    // Payment Status
    'payment_status_pending' => 'Menunggu Pembayaran',
    'payment_status_paid' => 'Sudah Dibayar',
    'payment_status_failed' => 'Pembayaran Gagal',
    'payment_status_expired' => 'Pembayaran Kedaluwarsa',
    'payment_status_cancelled' => 'Pembayaran Dibatalkan',
    'payment_expires_at' => 'Batas pembayaran',
    'payment_method_unknown' => 'Tidak Diketahui',
    'order_id' => 'ID Pesanan',

    // Form Labels
    'enter_customer_name' => 'Masukkan nama pelanggan',
    'select_service' => 'Pilih Layanan',
    'select_hairstyle' => 'Pilih Gaya Rambut',
    'description_notes' => 'Deskripsi / Catatan Khusus',
    'description_placeholder' => 'Tambahkan catatan khusus untuk booking Anda (opsional)',

    // Current Information
    'current_information' => 'Informasi Saat Ini',
    'update_booking_info' => 'Perbarui informasi booking Anda',

    // Action Buttons
    'view_detail' => 'Lihat Rincian',
    'back' => 'Kembali',
    'cancel' => 'Batalkan',
    'save_changes' => 'Simpan Perubahan',
    'pay_now' => 'Bayar Sekarang',
    'pay_cash' => 'Bayar Tunai',
    'view_transaction' => 'Lihat Transaksi',
    'view_feedback' => 'Lihat Umpan Balik',
    'give_feedback' => 'Berikan Umpan Balik',
    'new_booking' => 'Pemesanan Baru',
    'create_new_booking' => 'Buat Pemesanan Baru',

    // Empty State
    'no_bookings_title' => 'Belum Ada Pemesanan',
    'no_bookings_description' => 'Anda belum memiliki riwayat booking. Mulai buat booking pertama Anda!',

    // Validation Messages
    'no_services_available' => 'Tidak ada layanan tersedia saat ini.',
    'no_hairstyles_available' => 'Tidak ada gaya rambut tersedia saat ini.',
    'name_required' => 'Nama wajib diisi',
    'service_required' => 'Layanan wajib dipilih',
    'hairstyle_required' => 'Gaya rambut wajib dipilih',
    'datetime_required' => 'Tanggal dan waktu wajib diisi',
    'closed_sunday' => 'Maaf, kami tutup pada hari Minggu',
    'business_hours' => 'Pemesanan hanya dapat dilakukan antara jam 11:00 - 22:00',
    'advance_booking' => 'Pemesanan harus dilakukan minimal 24 jam sebelumnya',
    'slot_not_available' => 'Slot waktu tidak tersedia',
    'slot_conflict' => 'Jadwal bertabrakan dengan booking lain',

    // Important Information
    'important_info' => 'Informasi Penting',
    'info_time_change' => 'Perubahan tanggal dan waktu mungkin mempengaruhi nomor pesanan Anda',
    'info_service_change' => 'Jika mengubah layanan, total harga akan dihitung ulang',
    'info_edit_restriction' => 'Pemesanan hanya dapat diubah jika status masih "Pending" atau "Confirmed"',

    // JavaScript Messages
    'booking_success' => 'Pemesanan Berhasil!',
    'booking_created_for' => 'Pemesanan berhasil dibuat atas nama',
    'queue_number_label' => 'Nomor Pesanan Anda',
    'please_arrive_on_time' => 'Silakan datang sesuai jadwal yang telah ditentukan',
    'ok' => 'Oke',
    'success' => 'Berhasil',
    'oops' => 'Oops',
    'validation_error' => 'Kesalahan Validasi',
    'update_failed' => 'Update Gagal',
    'try_again' => 'Coba Lagi',
    'error_occurred' => 'Terjadi Kesalahan',

    // Cancellation Messages
    'confirm_cancel_booking' => 'Batalkan Pemesanan?',
    'cancel_warning_message' => 'Peringatan Pembatalan',
    'yes_cancel_booking' => 'Ya, Batalkan',
    'no_keep_booking' => 'Tidak, Tetap Simpan',
    'cancelling_booking' => 'Membatalkan Pemesanan...',
    'booking_cancelled_successfully' => 'Pemesanan berhasil dibatalkan',
    'cancel_failed' => 'Pembatalan Gagal',
    'are_you_sure_cancel' => 'Apakah Anda yakin ingin membatalkan pemesanan',
    'booking_will_be_cancelled' => 'Pemesanan akan dibatalkan secara permanen',
    'transaction_will_be_cancelled' => 'Transaksi pembayaran akan dibatalkan otomatis',
    'action_cannot_be_undone' => 'Tindakan ini tidak dapat dibatalkan',
    'please_wait_cancelling' => 'Mohon tunggu, sedang memproses pembatalan',
    'error' => 'Kesalahan',

    // Confirmation Messages
    'are_you_sure' => 'Apakah Anda yakin?',
    'booking_will_be_cancelled' => 'Pemesanan akan dibatalkan!',
    'yes_cancel' => 'Ya, batalkan!',

    // Feedback
    'rating' => 'Penilaian',
    'comment' => 'Komentar',
    'public_feedback' => 'Jadikan umpan balik publik',
    'submit_feedback' => 'Kirim Umpan Balik',
    'rating_required' => 'Silakan pilih penilaian',
    'comment_required' => 'Silakan berikan komentar Anda',
    'booking_created_successfully' => 'Pemesanan berhasil dibuat atas nama',
    
    // Payment Messages
    'payment_success' => 'Pembayaran Berhasil',
    'payment_failed' => 'Pembayaran Gagal',
    'payment_cancelled' => 'Pembayaran dibatalkan',
    'payment_pending' => 'Menunggu Pembayaran',
    
    // Pemesanan Status
    'status_pending' => 'Menunggu',
    'status_confirmed' => 'Dikonfirmasi',
    'status_completed' => 'Selesai',
    'status_cancelled' => 'Dibatalkan',
    
    // Pemesanan Actions
    'view_booking' => 'Lihat Pemesanan',
    'cancel_booking' => 'Batalkan Pemesanan',
    'confirm_booking' => 'Konfirmasi Pemesanan',
    'complete_booking' => 'Selesaikan Pemesanan',
    
    // Queue System
    'queue_number' => 'Nomor Pesanan',
    'current_queue' => 'Pesanan Saat Ini',
    'your_turn' => 'Giliran Anda',
    'please_wait' => 'Mohon Tunggu',
    
    // Notifications
    'booking_confirmed' => 'Pemesanan Anda telah dikonfirmasi',
    'booking_completed' => 'Pemesanan Anda telah selesai',
    'booking_cancelled' => 'Pemesanan Anda telah dibatalkan',
    
    // Time & Date
    'select_date' => 'Pilih Tanggal',
    'select_time' => 'Pilih Waktu',
    'available_time' => 'Waktu Tersedia',
    'unavailable_time' => 'Waktu Tidak Tersedia',
    
    // Pemesanan History
    'booking_history_title' => 'Riwayat Pemesanan',
    'booking_history_subtitle' => 'Lihat riwayat pemesanan Anda di sini.',
    'booking_number' => 'Pemesanan #',
    'no_bookings' => 'Belum Ada Pemesanan',
    'no_bookings_message' => 'Anda belum memiliki riwayat booking.',
    'make_booking_now' => 'Buat Pemesanan Sekarang',
    
    // Currency and Formatting
    'currency_symbol' => 'Rp',
    'currency_format_thousands_separator' => '.',
    'currency_format_decimal_separator' => ',',
    
    // Form Validation
    'required_field' => 'Wajib diisi',
    'optional_field' => 'Opsional',

    // Business Hours Error Messages
    'business_hours_error' => 'Jam operasional kami adalah 11:00 - 22:00. Silakan pilih waktu dalam jam operasional.',
    'booking_warning' => 'Peringatan: Waktu yang dipilih mendekati jam tutup. Pastikan Anda dapat selesai sebelum jam 22:00.',
    
    // Time conflict messages
    'time_conflict' => 'Waktu yang dipilih bentrok dengan booking lain. Silakan pilih waktu lain.',
    'slot_available' => 'Slot tersedia: :time',
    'alternative_slots' => 'Slot Alternatif:',
];
