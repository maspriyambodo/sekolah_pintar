@startuml Seq_Login
skinparam sequenceArrowThickness 2
skinparam roundcorner 10
skinparam sequenceParticipant underline

actor "User\n(Admin/Guru/Siswa/Wali)" as User
participant "Browser" as Browser
participant "AuthController" as Auth
participant "sys_users" as DB_User
participant "sys_user_roles" as DB_Role
participant "sys_login_logs" as DB_Log
participant "sessions" as DB_Session

User -> Browser : Buka halaman login
Browser -> Auth : POST /login\n{email, password}
activate Auth

Auth -> DB_User : findByEmail(email)
activate DB_User
DB_User --> Auth : userData / null
deactivate DB_User

alt User tidak ditemukan
  Auth -> DB_Log : insert(email, status=GAGAL, ip, user_agent)
  Auth --> Browser : 401 "Email tidak terdaftar"
  Browser --> User : Tampilkan pesan error
else User ditemukan
  Auth -> Auth : verifyPassword(password, hash)
  alt Password salah
    Auth -> DB_Log : insert(user_id, status=GAGAL, ip, user_agent)
    Auth --> Browser : 401 "Password salah"
    Browser --> User : Tampilkan pesan error
  else Password benar
    Auth -> DB_User : cek is_active
    alt User tidak aktif
      Auth -> DB_Log : insert(user_id, status=NONAKTIF, ip, user_agent)
      Auth --> Browser : 403 "Akun dinonaktifkan"
    else User aktif
      Auth -> DB_Role : getUserRoles(user_id)
      activate DB_Role
      DB_Role --> Auth : roles[]
      deactivate DB_Role
      Auth -> DB_Session : createSession(user_id, ip, user_agent)
      Auth -> DB_Log : insert(user_id, status=SUKSES, ip, user_agent)
      Auth --> Browser : 200 + redirect dashboard
      Browser --> User : Tampilkan Dashboard\nsesuai role
    end
  end
end
deactivate Auth
@enduml