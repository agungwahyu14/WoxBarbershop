# Database Restore Logging Documentation

## Overview
Comprehensive logging has been added to the database restore functionality in `SystemController@restore()` to track all aspects of the restore process for security, debugging, and audit purposes.

## Added Logging Points

### 1. Restore Initiation
**Log Level:** Info  
**When:** When restore process begins  
**Data Logged:**
- User ID and email
- IP address and User Agent
- Timestamp

### 2. File Upload Details
**Log Level:** Info  
**When:** After file is uploaded  
**Data Logged:**
- Original filename
- File size and MIME type
- File extension
- User ID

### 3. File Validation
**Log Level:** Info/Warning/Error  
**When:** During file validation steps  
**Data Logged:**
- Temporary file storage details
- File content validation results
- SQL command detection results
- Validation failures with reasons

### 4. Database Configuration
**Log Level:** Info/Error  
**When:** Database config is retrieved and validated  
**Data Logged:**
- Database connection details (host, port, database name)
- Configuration validation results

### 5. MySQL Client Detection
**Log Level:** Info/Warning/Error  
**When:** Checking for MySQL client availability  
**Data Logged:**
- MySQL client search results
- Alternative paths checked
- Found MySQL client location

### 6. Database Connection Test
**Log Level:** Info/Error  
**When:** Testing database connection before restore  
**Data Logged:**
- Connection test command execution
- Connection success/failure details
- Error output if connection fails

### 7. Pre-Restore Backup
**Log Level:** Info/Warning  
**When:** Creating backup of current database before restore  
**Data Logged:**
- Backup file location and size
- Backup creation success/failure
- Backup command output

### 8. Restore Execution
**Log Level:** Info/Error  
**When:** During actual database restore  
**Data Logged:**
- Restore command execution details
- Restore duration and performance metrics
- Success/failure status with detailed error output

### 9. Restore Completion
**Log Level:** Info/Error  
**When:** Restore process completes (success or failure)  
**Data Logged:**
- Complete restore statistics
- Total process duration
- Final status and completion timestamp
- User details and IP for audit trail

### 10. Exception Handling
**Log Level:** Error  
**When:** Any unexpected error occurs  
**Data Logged:**
- Exception details (message, file, line)
- Stack trace
- Process duration up to failure point
- Cleanup actions taken

## Log Sample Structure

```php
// Successful restore log entry example
[2024-11-06 10:30:45] local.INFO: Database restore completed successfully {
    "filename": "1699266645_backup_partial_2024-11-06_10-30-45.sql",
    "original_filename": "backup_partial_2024-11-06_10-30-45.sql",
    "file_size": 2048576,
    "restore_duration_seconds": 15.23,
    "total_duration_seconds": 18.67,
    "user_id": 1,
    "user_email": "admin@example.com",
    "ip_address": "127.0.0.1",
    "completed_at": "2024-11-06 10:30:45"
}

// Failed restore log entry example
[2024-11-06 10:35:22] local.ERROR: Database restore failed {
    "filename": "1699266922_corrupted_file.sql",
    "original_filename": "corrupted_file.sql",
    "return_code": 1,
    "restore_duration_seconds": 2.15,
    "output": "ERROR 1064 (42000): You have an error in your SQL syntax",
    "user_id": 1,
    "user_email": "admin@example.com",
    "ip_address": "127.0.0.1",
    "failed_at": "2024-11-06 10:35:22"
}
```

## Benefits

1. **Security Audit Trail**: Track who performs restore operations and when
2. **Debugging**: Detailed error information for troubleshooting failed restores
3. **Performance Monitoring**: Track restore duration and identify bottlenecks
4. **Compliance**: Maintain records of data restoration activities
5. **User Activity**: Monitor user behavior and potential misuse

## Log File Location

All restore logs are written to: `storage/logs/laravel.log`

## Viewing Logs

You can monitor restore operations in real-time using:

```bash
tail -f storage/logs/laravel.log | grep "restore"
```

Or filter by specific log levels:

```bash
tail -f storage/logs/laravel.log | grep "ERROR.*restore"
```

## Security Considerations

- Database passwords are never logged (using `escapeshellarg()`)
- Sensitive information is excluded from log output
- IP addresses and user agents are logged for security tracking
- File content is only logged partially (first 500 characters for validation errors)

## Integration with Existing Backup Logs

The restore logging complements the existing backup logging functionality, providing a complete audit trail of all database operations in the system.