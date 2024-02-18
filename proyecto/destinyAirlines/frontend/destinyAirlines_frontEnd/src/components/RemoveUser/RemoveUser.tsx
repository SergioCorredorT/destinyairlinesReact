import React, { useState } from "react";
import styles from "./RemoveUser.module.css";
import { deleteAccount } from "../../services/deleteAccount";
import { authStore } from "../../store/authStore";

export const RemoveUser = () => {
  const [generalError, setGeneralError] = useState("");
  const { emailAddress, refreshToken, signOut } = authStore();
  const handleSubmitDeleteAccount = (
    event: React.FormEvent<HTMLFormElement>
  ) => {
    event.preventDefault();
    if (!confirm("¿Esta seguro de eliminar cuenta?")) {
      return;
    }
    const formData = Object.fromEntries(new FormData(event.currentTarget));
    const password = formData.password;

    deleteAccount({ emailAddress, refreshToken, password }).then((data) => {
      if (!data.status) {
        setGeneralError(data.message);
      } else {
        signOut();
      }
    });
  };

  return (
    <>
      <form onSubmit={handleSubmitDeleteAccount}>
        <div className={styles.inputContainer}>
          <label htmlFor="RU_password">Password</label>
          <input
            type="password"
            name="password"
            id="RU_password"
            placeholder="Password"
          />
        </div>
        <div className={styles.buttonsContainer}>
          <button type="submit" className="bg_danger">
            Borrar mi cuenta
          </button>
        </div>
      </form>
      {generalError && (
        <div className={styles.errorMessage}>
          <p>{generalError}</p>
        </div>
      )}
    </>
  );
};
