import { useState } from "react";
import styles from "./ChangePassword.module.css";
import { changePassword } from "../../services/changePassword";
import { authStore } from "../../store/authStore";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { changePasswordSchema } from "../../validations/changePasswordSchema";

type Inputs = {
  password: string;
  confirmPassword: string;
};

export const ChangePassword = () => {
  const [generalError, setGeneralError] = useState("");
  const { emailAddress, accessToken, signOut } = authStore();
  const {
    register,
    handleSubmit,
    getValues,
    formState: { errors: formErrors },
  } = useForm<Inputs>({
    resolver: zodResolver(changePasswordSchema),
  });

  const handleSubmitChangePassword = handleSubmit(async (jsonData) => {
    if (!confirm("¿Está seguro de cambiar de contraseña?")) {
      return;
    }

    const currentValues = getValues();
    const response = await changePassword({
      emailAddress,
      accessToken,
      password: currentValues["password"],
    });

    if(!response) {
      setGeneralError("Error en la petición a servidor");
      return;
    }

    if (response.status) {
      signOut();
    } else {
      setGeneralError(response.message);
    }
  });

  return (
    <>
      <form onSubmit={handleSubmitChangePassword}>
        <div className={styles.inputContainer}>
          {formErrors.password ? (
            <label htmlFor="password" className={styles.errorMessage}>
              {formErrors.password.message}
            </label>
          ) : (
            <label htmlFor="password">Nuevo password</label>
          )}
          <input
            type="password"
            id="password"
            placeholder="Password"
            {...register("password")}
          />
        </div>
        <div className={styles.inputContainer}>
          {formErrors.confirmPassword ? (
            <label htmlFor="confirmPassword" className={styles.errorMessage}>
              {formErrors.confirmPassword.message}
            </label>
          ) : (
            <label htmlFor="confirmPassword">Repite password</label>
          )}
          <input
            type="password"
            id="confirmPassword"
            placeholder="Repite password"
            {...register("confirmPassword")}
          />
        </div>
        <div className={styles.buttonsContainer}>
          <button type="submit">Cambiar contraseña</button>
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
