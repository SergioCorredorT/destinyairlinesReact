import styles from "./SignIn.module.css";
import { customFetch } from "../../services/fetchUtils";
import { useAuthStore } from "../../store/authStore";
import { useState } from "react";

export function SignIn({ isOpen }) {
  const [error, setError] = useState(null);

  const {
    setAccessToken,
    setRefreshToken,
    setTitle,
    setFirstName,
    setLastName,
    setIsLoggedIn,
  } = useAuthStore();

  const handleSubmit = async (event) => {
    event.preventDefault();

    const formData = Object.fromEntries(new FormData(event.target));

    const response = await customFetch(
      "http://localhost/destinyairlinesReact/proyecto/destinyAirlines/backend/MainController.php",
      formData
    );

    if(response.error){
      setError(response.error)
      return ;
    }

    if (response && response.status) {
      setAccessToken(response.tokens.accessToken);
      setRefreshToken(response.tokens.refreshToken);
      setTitle(response.response.userData.title);
      setFirstName(response.response.userData.firstName);
      setLastName(response.response.userData.lastName);
      setIsLoggedIn(true);
      isOpen(false);
    }
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
        {error && <div className={styles.errorMessage}><p>{error}</p></div>}
      </form>
    </div>
  );
}
