# TODO List - Fix Route & Pinjam-Kembali Flow

## Plan Approved - Implementing Changes

### Step 1: Update Routes.php ✅
- [x] Add `client/proses_pinjam` POST route
- [x] Add `client/konfirmasi_kembali` GET route

### Step 2: Update halaman_pinjam.php ✅
- [x] Add CSRF token to the form

### Step 3: Update Client Controller ✅
- [x] Add `proses_pinjam()` method to handle borrowing
- [x] Update `scan_check()` method to check if user already borrowed the item
  - If borrowed: directly return (no confirmation) → save to history → go to home
  - If not borrowed: show confirmation page → after submit → go to home

### Step 4: Test the flow
- [ ] Scan once to borrow
- [ ] Scan again to return (directly, without confirmation)

## Summary of Changes:
1. **Routes.php**: Added `post 'proses_pinjam' => 'Client::proses_pinjam'`
2. **halaman_pinjam.php**: Added `<?= csrf_field() ?>` to form
3. **Client.php**: 
   - Added `proses_pinjam()` method
   - Updated `scan_check()` to handle both borrow and return in one flow
   - When user scans a borrowed item: automatically returns it and saves to history


