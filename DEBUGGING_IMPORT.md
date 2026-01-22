# Debugging Student Import Issues

## Error: "Error uploading chunk 1: unknown status"

This error means the AJAX request is failing. Follow these steps to debug:

### 1. Check Browser Console
- Open the browser's Developer Tools (F12)
- Go to the **Console** tab
- Look for detailed error messages about the chunk upload
- The console will show:
  - Status code (200, 400, 500, etc.)
  - Response body
  - Exact error message

### 2. Check Network Tab
- Go to **Network** tab in DevTools
- Reload the page and try importing again
- Look for the POST request to `students_import_store`
- Click on it and check:
  - **Status** code
  - **Response** body - this will show the actual error
  - **Request headers** - should include CSRF token

### 3. Check Server Logs
- Look at Laravel logs: `storage/logs/laravel.log`
- This will show if there's a PHP error on the server side
- Command to check:
  ```bash
  tail -f storage/logs/laravel.log
  ```

### 4. Common Issues & Solutions

**Issue: 419 (CSRF Token Mismatch)**
- Solution: The CSRF token might not be loading correctly
- Check if `<meta name="csrf-token">` exists in the HTML

**Issue: 404 (Route Not Found)**
- Solution: The route name might be wrong
- Check routes/web.php for the correct route name
- Current route: `students.import_store`

**Issue: 500 (Server Error)**
- Solution: PHP error on the backend
- Check `storage/logs/laravel.log` for the actual error
- Common causes:
  - Missing database columns
  - Invalid model fillable fields
  - Database constraints violated

**Issue: 422 (Validation Failed)**
- Solution: Required fields are missing
- Check that all students have:
  - `candidate_name` (required)
  - `occupation_id` (required)
  - `district_id` (required)

### 5. What Each Column Shows

The progress UI will show:
- **Progress Bar**: Percentage complete
- **Count**: X / Y students imported
- **Chunk Status**: Which chunk is being processed

### 6. Real-Time Debugging

Open browser console before clicking "Save All Students":
```javascript
// You can type this in console to debug
console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
console.log('Route URL:', '{{ route("students.import_store") }}');
console.log('Total rows:', $('table tbody tr').length);
```

## Next Steps

1. **Click "Save All Students"**
2. **Open Browser Console (F12 → Console)**
3. **Look at the detailed error message**
4. **Share the error message from the console** - this will help fix the issue
5. **Check Laravel logs** if you see "500 Server Error"

---

## For Developers

The chunking works by:
1. Converting form data into 50-student chunks
2. Sending each chunk via AJAX POST
3. Server processes chunk and returns JSON response
4. Client shows progress and sends next chunk
5. When all chunks done, redirects to students list

The new error handler will show:
- ✓ Success (200 status)
- ✗ Detailed error with status code
- Complete response body for debugging
