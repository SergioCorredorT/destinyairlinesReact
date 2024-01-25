import styles from "./SignIn.module.css";
import { useAuthStore } from "../../store/authStore";
import { useState } from "react";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { signInSchema } from "../../validations/signInSchema";

type Inputs = {
  emailAddress: string;
  password: string;
};

export function SignIn() {
  const [error, setError] = useState<string | null>(null);
  const { signIn } = useAuthStore();
  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<Inputs>({
    resolver: zodResolver(signInSchema),
  });
  //Register :Para señalar los inputs a tener en cuenta en react-hook-form
  //handleSubmit :Para enviar los datos al backend dándole el esquema de validación o resolver de tipo zod

  const onSubmit = handleSubmit((jsonData) => {
    //Tras ser validado el form con el schema
    signIn({
      emailAddress: jsonData.emailAddress,
      password: jsonData.password,
    }).then((data) => {
      if (!data.status) {
        setError(data.message);
      }
    });
  });

  return (
    <div className={styles.signIn}>
      <h2>Iniciar sesión</h2>
      <form className={styles.form} onSubmit={onSubmit}>
        <div className={styles.inputGroup}>
          {errors.emailAddress ? (
            <label htmlFor="emailAddress" className={styles.errorMessage}>
              {errors.emailAddress.message}
            </label>
          ) : (
            <label htmlFor="emailAddress">Email</label>
          )}
          <input
            type="text"
            id="emailAddress"
            placeholder="Email address"
            title="Introduzca aquí su email"
            {...register("emailAddress")}
          />
        </div>
        <div className={styles.inputGroup}>
          {errors.password ? (
            <label htmlFor="password" className={styles.errorMessage}>
              {errors.password.message}
            </label>
          ) : (
            <label htmlFor="password">Password</label>
          )}
          <input
            type="password"
            id="password"
            placeholder="Password"
            {...register("password")}
          />
        </div>
        <div className={styles.buttonsContainer}>
          <button type="submit">Iniciar sesión</button>
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
