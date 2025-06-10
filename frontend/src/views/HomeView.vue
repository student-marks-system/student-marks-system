<template>
  <div class="login-wrapper">
    <div class="login-card">
      <h2>Welcome back!</h2>
      <form @submit.prevent="login">
        <input type="text" v-model="username" placeholder="Email address" required />
        <input type="password" v-model="password" placeholder="Password" required />
        <button type="submit">Login</button>
        <p class="error-msg">{{ error }}</p>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      username: '',
      password: '',
      error: ''
    };
  },
  methods: {
    async login() {
      try {
        const response = await fetch('http://localhost:8000/api/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            username: this.username,
            password: this.password
          }),
         // credentials: 'include' // ✅ Required for session cookies
        });

        if (!response.ok) {
          const errorData = await response.json();
          this.error = errorData.message || 'Login failed';
          return;
        }

        const data = await response.json();

        // ✅ Role-based redirection
        switch (data.role) {
          case 'student':
            this.$router.push('/student');
            break;
          case 'lecturer':
            this.$router.push('/lecturer');
            break;
          case 'admin':
            this.$router.push('/admin');
            break;
          default:
            this.error = 'Unknown role';
        }

      } catch (err) {
        this.error = 'Server error or network issue';
      }
    }
  }
};
</script>
<style scoped>
.login-wrapper {
  height: 100vh;
  background: linear-gradient(135deg, #6e0f1d, #c93b3b); /* Maroon-red gradient */
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Segoe UI', sans-serif;
}

.login-card {
  background: #fff;
  border-radius: 12px;
  padding: 40px 30px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  width: 100%;
  max-width: 400px;
  text-align: center;
}

.login-card h2 {
  margin-bottom: 20px;
  color: #6e0f1d;
}

form input {
  width: 100%;
  padding: 12px;
  margin: 10px 0;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 16px;
}

form button {
  width: 100%;
  padding: 12px;
  background: #9d1b2c;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  cursor: pointer;
  margin-top: 10px;
}

form button:hover {
  background: #b3222f;
}

.error-msg {
  margin-top: 10px;
  color: red;
  font-size: 14px;
}
</style>