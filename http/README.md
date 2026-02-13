# HTTP REST Client - Sekolah API

This directory contains HTTP client files for testing the Sekolah API. These files are compatible with:
- **VS Code REST Client** extension (humao.rest-client)
- **JetBrains IDEs** (IntelliJ IDEA, PHPStorm, WebStorm)
- **HTTPie** (with some modifications)
- **cURL** (with some modifications)

## 📁 File Structure

```
http/
├── README.md           # This documentation
├── auth.http           # Authentication endpoints
├── siswa.http          # Student (Siswa) endpoints
├── files.http          # File upload endpoints
├── health.http         # Health check & API info
└── auth/
    └── login.http      # Simple login example
```

## 🚀 Quick Start

### 1. Install VS Code REST Client Extension

Install the [REST Client](https://marketplace.visualstudio.com/items?itemName=humao.rest-client) extension by Huachao Mao.

### 2. Set Base URL

All HTTP files use the variable `@baseUrl`. You can change it at the top of each file:

```http
@baseUrl = http://localhost:8000/api/v1
```

### 3. Set Authentication Token

For protected endpoints, set your JWT token:

```http
@authToken = YOUR_ACCESS_TOKEN_HERE
```

Or use the dynamic token from login response (auto-captured):

```http
Authorization: Bearer {{login.response.body.data.access_token}}
```

### 4. Send Requests

Click the `Send Request` link that appears above each request, or use the command palette (`Ctrl+Shift+P` → `REST Client: Send Request`).

## 🔐 Authentication Flow

### Step 1: Login

Open `auth.http` and send the **Login** request:

```http
POST {{baseUrl}}/auth/login
Content-Type: application/json

{
  "email": "admin@sekolah.test",
  "password": "password123"
}
```

### Step 2: Copy Access Token

From the response, copy the `access_token`:

```json
{
  "success": true,
  "data": {
    "access_token": "eyJhbGciOiJIUzI1NiIs...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```

### Step 3: Use Token in Other Requests

Paste the token in other HTTP files:

```http
@authToken = eyJhbGciOiJIUzI1NiIs...
```

Or use the auto-captured variable from the login request:

```http
GET {{baseUrl}}/siswa
Authorization: Bearer {{login.response.body.data.access_token}}
```

## 📚 Available Endpoints

### Authentication (`auth.http`)

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/auth/login` | Login with credentials | No |
| POST | `/auth/register` | Register new user | No |
| POST | `/auth/refresh` | Refresh access token | No |
| POST | `/auth/logout` | Logout user | Yes |
| GET | `/auth/me` | Get current user | Yes |

### Siswa (Students) (`siswa.http`)

| Method | Endpoint | Description | Auth Required | Role |
|--------|----------|-------------|---------------|------|
| GET | `/siswa` | List all students | Yes | admin, guru |
| POST | `/siswa` | Create new student | Yes | admin, guru |
| GET | `/siswa/{id}` | Get student by ID | Yes | admin, guru |
| PUT | `/siswa/{id}` | Update student | Yes | admin, guru |
| DELETE | `/siswa/{id}` | Delete student | Yes | admin, guru |
| GET | `/siswa/kelas/{kelasId}` | Get students by class | Yes | admin, guru |
| GET | `/siswa/{id}/absensi-summary` | Get attendance summary | Yes | admin, guru |
| POST | `/siswa/{id}/naik-kelas` | Promote student | Yes | admin, guru |
| POST | `/siswa/{id}/lulus` | Graduate student | Yes | admin, guru |

### File Upload (`files.http`)

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/files/upload` | Upload file | Yes |
| POST | `/files/presigned-url` | Get presigned URL | Yes |
| DELETE | `/files/delete` | Delete file | Yes |

### Health Check (`health.http`)

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/health` | API health check | No |

## 📝 Request Examples

### Login

```http
POST http://localhost:8000/api/v1/auth/login
Content-Type: application/json

{
  "email": "admin@sekolah.test",
  "password": "password123"
}
```

### Create Siswa

```http
POST http://localhost:8000/api/v1/siswa
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "sys_user_id": 2,
  "nis": "2024002",
  "nama": "Ani Wulandari",
  "jenis_kelamin": "P",
  "tanggal_lahir": "2008-08-20",
  "alamat": "Jl. Sudirman No. 45",
  "mst_kelas_id": 1,
  "status": "aktif"
}
```

### Upload File

```http
POST http://localhost:8000/api/v1/files/upload
Authorization: Bearer YOUR_TOKEN
Content-Type: multipart/form-data; boundary=----WebKitFormBoundary

------WebKitFormBoundary
Content-Disposition: form-data; name="file"; filename="document.pdf"
Content-Type: application/pdf

< ./path/to/document.pdf
------WebKitFormBoundary--
```

## 🔧 Variables

### Global Variables (defined in each file)

```http
@baseUrl = http://localhost:8000/api/v1
@authToken = YOUR_TOKEN_HERE
@contentType = application/json
```

### Dynamic Variables (auto-captured from responses)

```http
# @name login
POST {{baseUrl}}/auth/login
...

# Use the token from login response
GET {{baseUrl}}/auth/me
Authorization: Bearer {{login.response.body.data.access_token}}
```

## 🛠️ Environment Variables (VS Code)

Create `.vscode/settings.json` to define environment-specific variables:

```json
{
  "rest-client.environmentVariables": {
    "$shared": {
      "version": "v1"
    },
    "local": {
      "baseUrl": "http://localhost:8000/api/v1",
      "authToken": ""
    },
    "development": {
      "baseUrl": "https://dev-api.sekolah.test/api/v1",
      "authToken": ""
    },
    "production": {
      "baseUrl": "https://api.sekolah.test/api/v1",
      "authToken": ""
    }
  }
}
```

Then use them in requests:

```http
GET {{baseUrl}}/siswa
Authorization: Bearer {{authToken}}
```

Switch environments using the command palette: `REST Client: Switch Environment`

## ⚠️ Common Issues

### 401 Unauthorized
- Token may have expired (expires in 1 hour)
- Try refreshing the token or login again

### 403 Forbidden
- Your user role doesn't have permission
- Required roles: admin, guru

### 422 Validation Error
- Check request body format
- Verify all required fields are provided
- See validation rules in each HTTP file

### Connection Refused
- Make sure the Laravel server is running
- Default: `php artisan serve` on port 8000

## 📖 Response Format

All API responses follow this structure:

```json
{
  "success": true|false,
  "message": "Human-readable message",
  "data": { ... },
  "errors": { ... }  // Only present for validation errors
}
```

## 🔗 Useful Links

- [VS Code REST Client Extension](https://marketplace.visualstudio.com/items?itemName=humao.rest-client)
- [REST Client Documentation](https://marketplace.visualstudio.com/items?itemName=humao.rest-client#usage)
- [Laravel API Documentation](../API_DOCUMENTATION.md)
