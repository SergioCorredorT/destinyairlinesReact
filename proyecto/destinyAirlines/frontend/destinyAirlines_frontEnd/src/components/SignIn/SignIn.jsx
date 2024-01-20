import styles from "./SignIn.module.css";
import { customFetch } from "../../services/fetchUtils";
import { useState } from "react";

export function SignIn() {
  const [isLogin, setIsLogin] = useState(false);//Pasar esto a un contexto global
  const handleSubmit = async (event) => {
    event.preventDefault();

    const formData = Object.fromEntries(new FormData(event.target));
    const data = await customFetch(
      "http://localhost/destinyairlinesReact/proyecto/destinyAirlines/backend/MainController.php",
      formData
    );

    if (data) {
      setIsLogin(formData.emailAddress);
      //Guardar tokens en localStorage y el email
    }

    console.log(data);
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
      </form>
    </div>
  );
}
