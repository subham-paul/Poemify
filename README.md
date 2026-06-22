# ✨ Poemify

**Poemify** is a modern **AI-powered Poetry Generator** that transforms your ideas into beautifully crafted poems using the **Google Gemini API**. Designed with a sleek **dark-themed glassmorphism interface**, Poemify offers an immersive writing experience where users can generate, save, listen to, and share unique poems across multiple poetic styles and formats.

> **Turn your imagination into poetry with the power of Artificial Intelligence.**

---

## 🌟 Features

### 🤖 AI-Powered Poetry Generation

- Generate unique poems using **Google Gemini AI**
- Create poems from custom prompts
- Multiple poem types and writing styles
- Adjustable poem length
- High-quality AI-generated creative content

### 📝 Multiple Poem Types

- 📖 Narrative
- 🌹 Sonnet
- 🍃 Haiku
- 🎵 Lyric
- ⚔️ Epic
- ✨ Free Verse
- 🎭 Ballad
- *(More can easily be added.)*

### 🎨 Writing Styles

- Romantic
- Contemporary
- Gothic
- Inspirational
- Nature
- Emotional
- Fantasy
- Classical
- Modern

### 👤 User Authentication

- Secure Registration
- Email & Password Login
- Google OAuth Login
- Protected User Dashboard
- Session Management

### 📚 My Collection

- Save generated poems
- Personal poem library
- View poem history
- Card-based layout
- Read full poems inside elegant modal windows

### 🔊 Interactive Features

- 🔈 Text-to-Speech
- ⏸ Pause Reading
- ▶ Resume Reading
- 📋 Copy Poem
- 📤 Share Poem
- ❤️ Save Favorite Poems

### 🎨 Beautiful User Experience

- Modern Dark Theme
- Glassmorphism UI
- Animated Bubble Background
- Smooth Animations
- Responsive Layout
- Professional Typography
- Dynamic Glow Effects

---

# 🛠️ Tech Stack

## Backend

- PHP 8+
- MySQL

## Artificial Intelligence

- Google Gemini API

## Frontend

- HTML5
- CSS3
- Bootstrap 5
- JavaScript
- AJAX

## Authentication

- Email & Password Authentication
- Google OAuth

## Database

- MySQL

## Environment Management

- `.env`

---

# 📚 Technologies Used

| Technology | Purpose |
|------------|---------|
| **PHP** | Backend Development |
| **MySQL** | Database Management |
| **Google Gemini API** | AI Poem Generation |
| **Bootstrap 5** | Responsive UI |
| **JavaScript** | Interactive Features |
| **AJAX** | Asynchronous Requests |
| **Google OAuth** | Secure Login |
| **SpeechSynthesis API** | Text-to-Speech |
| **Dotenv** | Secure Environment Variables |

---

# 📂 Project Structure

```text
Poemify/
│
├── index.php
├── login.php
├── register.php
├── generate.php
├── collection.php
├── logout.php
├── config/
│   ├── database.php
│   ├── gemini.php
│   └── oauth.php
│
├── includes/
│   ├── auth.php
│   ├── functions.php
│   └── header.php
│
├── templates/
│   ├── navbar.php
│   ├── footer.php
│   └── modals.php
│
├── assets/
│   ├── css/
│   ├── js/
│   ├── images/
│   └── fonts/
│
├── uploads/
│
├── .env
├── README.md
└── ...
```

---

# 🚀 Features Overview

- 🤖 AI Poetry Generation
- 🌹 Multiple Poem Types
- 🎨 Writing Style Selection
- 📏 Adjustable Poem Length
- 👤 User Authentication
- ☁ Google OAuth
- 📚 My Collection
- 🔊 Text-to-Speech
- 📋 Copy & Share
- 🌙 Glassmorphism Dark UI

---

# ⚙️ Installation

## 1️⃣ Clone the Repository

```bash
git clone https://github.com/subham-paul/Poemify.git
```

```bash
cd Poemify
```

---

## 2️⃣ Configure the Database

Create a MySQL database.

Example:

```sql
CREATE DATABASE poemify;
```

Import the provided SQL file.

---

## 3️⃣ Configure Environment Variables

Create a `.env` file.

```env
DB_HOST=localhost
DB_NAME=poemify
DB_USER=root
DB_PASSWORD=password

GEMINI_API_KEY=your_google_gemini_api_key

GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=http://localhost/Poemify/auth/google-callback.php
```

---

## 4️⃣ Start Your Local Server

Using XAMPP:

```text
htdocs/
    Poemify/
```

or using Laragon.

---

## 5️⃣ Visit

```
http://localhost/Poemify
```

---

# 🤖 How It Works

### Step 1 — User Login

Users can:

- Register
- Login
- Continue with Google

---

### Step 2 — Enter Prompt

Example:

```
Write a poem about hope during difficult times.
```

---

### Step 3 — Customize

Choose:

- Poem Type
- Writing Style
- Length

Example:

```
Type:
Haiku

Style:
Nature

Length:
Short
```

---

### Step 4 — AI Generation

Google Gemini receives the request and generates an original poem.

---

### Step 5 — Display Result

The generated poem appears instantly with options to:

- 🔈 Listen
- 📋 Copy
- 📤 Share
- ❤️ Save

---

### Step 6 — Save Collection

Logged-in users can store poems in **My Collection**.

Long poems include:

- **Read Full Poem**
- Modal Popup
- Easy Navigation

---

# 🧠 Application Workflow

```text
User Prompt
      │
      ▼
Poem Type Selection
      │
      ▼
Writing Style
      │
      ▼
Length Selection
      │
      ▼
Google Gemini API
      │
      ▼
AI Poem Generation
      │
      ▼
Display Result
      │
      ├─────────────┐
      ▼             ▼
Save Poem     Listen / Copy / Share
      │
      ▼
My Collection
```

---

# 📊 Applications

- Creative Writing
- Poetry Learning
- Literature Education
- Content Creation
- Storytelling
- Personal Journaling
- Writing Inspiration
- AI Demonstrations
- Educational Projects
- Digital Publishing

---

# 🚀 Future Enhancements

- 🎙 AI Voice Narration
- 🌍 Multi-language Poetry
- 🎭 AI Image Generation for Poems
- 📖 Export as PDF
- 📚 Public Poetry Gallery
- ❤️ Like & Comment System
- 👥 Community Sharing
- 🧠 Personalized Writing Suggestions
- 📱 Progressive Web App (PWA)
- ☁ Cloud Deployment

---

# 🔒 Security Features

- Password Hashing
- Google OAuth Authentication
- Environment Variables (`.env`)
- SQL Injection Protection
- XSS Protection
- CSRF Protection
- Session Management
- Secure API Key Storage

---

# 🤝 Contributing

Contributions are welcome!

1. Fork the repository.

2. Create a feature branch.

```bash
git checkout -b feature/NewFeature
```

3. Commit your changes.

```bash
git commit -m "Add New Feature"
```

4. Push your changes.

```bash
git push origin feature/NewFeature
```

5. Open a Pull Request.

---

# 🐞 Reporting Issues

If you encounter bugs or have suggestions for improvements, please create an issue with detailed information.

---

# 📜 License

This project is licensed under the **MIT License**.

---

# 👨‍💻 Author

## **Subham Paul**

Passionate about **Artificial Intelligence, Generative AI, PHP, Python, Machine Learning, Web Development, and Creative Technologies.**

- **GitHub:** https://github.com/subham-paul
- **LinkedIn:** https://www.linkedin.com/in/subham-paul-india/

---

# ⭐ Show Your Support

If you found this project useful:

- ⭐ Star this repository
- 🍴 Fork the project
- 🤝 Contribute
- 💬 Share your feedback

---

## 🙏 Acknowledgements

Special thanks to the open-source communities behind:

- PHP
- MySQL
- Google Gemini API
- Bootstrap
- JavaScript
- Google OAuth
- HTML5 & CSS3

for providing the technologies that made this project possible.

---

> **"Where imagination meets Artificial Intelligence—create timeless poetry with a single prompt."** ✨📖🤖
