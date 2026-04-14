# 📚 School Events Tracker System

A web-based application designed to efficiently manage and track school events. This system enables administrators to organize events, manage users, and control access, while students and staff can easily stay updated with upcoming activities.

Built with a focus on **clean UI/UX, secure authentication, and scalable architecture**, this project simplifies event coordination within educational institutions.

---

## ✨ Features

- 🔐 User Authentication (Login & Logout System)  
- 🧑‍💼 Role-Based Access Control (RBAC)  
- 📅 Event Creation, Update, and Deletion  
- 📋 Event Listing and Tracking  
- ⚡ AJAX-powered interactions (no page reloads)  
- 🎨 Responsive UI using Tailwind CSS  
- 🛡️ Secure backend with prepared statements (PDO)  

---

## 🛠️ Tech Stack

### **Frontend**
- HTML5  
- CSS3 / Tailwind CSS  
- JavaScript (Vanilla JS & jQuery)  

### **Backend**
- PHP  
- AJAX  

### **Database**
- MySQL  
- PDO (PHP Data Objects)  

### **Authentication & Security**
- Session Management  
- Role-Based Access Control (RBAC)  
- Input Validation & Sanitization  

### **Development Tools**
- Git & GitHub  
- XAMPP / Localhost Environment  

---

## 📁 Project Structure (Example)

```
/project-root
│── /assets          # CSS, JS, images
│── /controllers     # Handles requests (AJAX/API logic)
│── /models          # Database logic
│── /views           # UI components / pages
│── /config          # Database connection
│── index.php        # Entry point
```

---

## ⚙️ Installation & Setup

### **1. Clone the Repository**
```bash
git clone https://github.com/your-username/school-events-tracker.git
cd school-events-tracker
```

### **2. Move Project to Server Directory**
If using XAMPP, place the folder inside:
```
C:\xampp\htdocs\
```

### **3. Start Apache & MySQL**
- Open XAMPP Control Panel  
- Start **Apache** and **MySQL**

### **4. Setup the Database**
1. Open **phpMyAdmin**  
2. Create a new database:
```
school_events_db
```
3. Import the provided `.sql` file (if available)

---

### **5. Configure Database Connection**
Edit your database config file (e.g., `/config/database.php`):

```php
$host = 'localhost';
$dbname = 'school_events_db';
$username = 'root';
$password = '';
```

---

### **6. Run the Application**
Open your browser and go to:
```
http://localhost/school-events-tracker
```

---

## 🔑 Default Access (Optional)

| Role  | Email             | Password |
|-------|------------------|----------|
| Admin | admin@email.com  | password |
| User  | user@email.com   | password |

---

## 🔐 Security Notes

- Uses **PDO prepared statements** to prevent SQL Injection  
- Implements **session-based authentication**  
- RBAC ensures users only access authorized features  

---

## 🚀 Future Improvements

- 📱 Mobile-first enhancements  
- 📊 Dashboard analytics  
- 🔔 Notifications system  
- 🌐 API integration  

---

## 🤝 Contributing

1. Fork the repository  
2. Create a new branch  
3. Commit your changes  
4. Push and open a Pull Request  

---

## 📄 License

This project is open-source and available under the **MIT License**.
