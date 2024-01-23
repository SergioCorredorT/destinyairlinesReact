import styles from "./SignIn.module.css";
import { useAuthStore } from "../../store/authStore";
import { useState } from "react";

export function SignIn() {
  const [error, setError] = useState(null);
  const { signIn } = useAuthStore();

  const handleSubmit = async (event) => {
    event.preventDefault();
    const jsonData = Object.fromEntries(new FormData(event.target));
    signIn({emailAddress: jsonData.emailAddress, password: jsonData.password }).then((data) => {
      if(!data.status) {
        setError(data.message);
      }
    });
  };

  return (
    <div className={styles.signIn}>
      <h2>Sign in</h2>
      <form className={styles.form} onSubmit={handleSubmit}>
        <div className={styles.inputGroup}>
          <label htmlFor="emailAddress">Email</label>
          <input
            type="text"
            id="emailAddress"
            name="emailAddress"
            placeholder="juan@dominio.com"
            title="Introduzca aquí su email"
          />
        </div>
        <div className={styles.inputGroup}>
          <label htmlFor="password">Password</label>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="12345678A"
          />
        </div>
        <input type="hidden" name="command" value="loginUser" />
        <div className={styles.inputGroup}>
          <button type="submit">Sign in</button>
        </div>
        {error && (
          <div className={styles.errorMessage}>
            <p>{error}</p>
          </div>
        )}
      </form>
    </div>
  );
}
