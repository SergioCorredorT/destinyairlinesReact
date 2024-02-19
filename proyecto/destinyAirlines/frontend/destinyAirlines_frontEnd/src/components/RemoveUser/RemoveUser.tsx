import React, { useState } from "react";
import styles from "./RemoveUser.module.css";
import { deleteAccount } from "../../services/deleteAccount";
import { authStore } from "../../store/authStore";
import { removeUserSchema } from "../../validations/removeUserSchema";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";

type Inputs = {
  password: string;
};

export const RemoveUser = () => {
  const [generalError, setGeneralError] = useState("");
  const {
    register,
    handleSubmit,
    getValues,
    formState: { errors: formErrors },
  } = useForm<Inputs>({
    resolver: zodResolver(removeUserSchema),
  });
  const { emailAddress, refreshToken, signOut } = authStore();

  const handleSubmitDeleteAccount = handleSubmit(async (jsonData) => {
    if (!confirm("¿Esta seguro de eliminar cuenta?")) {
      return;
    }

    const currentValues = getValues();
    const response = await deleteAccount({
      emailAddress,
      refreshToken,
      password: currentValues["password"],
    });

    if (!response) {
      setGeneralError("Error en la petición a servidor");
      return;
    }

    if (!response.status) {
      setGeneralError(response.message);
    }
    signOut();
  });

  return (
    <>
      <form onSubmit={handleSubmitDeleteAccount}>
        <div className={styles.inputContainer}>
          {formErrors.password ? (
            <label htmlFor="RU_password" className={styles.errorMessage}>
              {formErrors.password.message}
            </label>
          ) : (
            <label htmlFor="RU_password">Password</label>
          )}
          <input
            type="password"
            id="RU_password"
            placeholder="Password"
            {...register("password")}
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
