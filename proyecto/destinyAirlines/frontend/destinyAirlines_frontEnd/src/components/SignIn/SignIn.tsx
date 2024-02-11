import styles from "./SignIn.module.css";
import { useAuthStore } from "../../store/authStore";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { signInSchema } from "../../validations/signInSchema";
import { useSignal } from "@preact/signals-react";
import { forgotPassword } from "../../services/forgotPassword";

type Inputs = {
  emailAddress: string;
  password: string;
};

export function SignIn() {
  const generalError = useSignal("");
  //const [generalError, setGeneralError] = useState<string | null>(null);
  const { signIn } = useAuthStore();
  const {
    register,
    handleSubmit,
    formState: { errors: formErrors },
  } = useForm<Inputs>({
    resolver: zodResolver(signInSchema),
  });
  //Register :Para señalar los inputs a tener en cuenta en react-hook-form
  //handleSubmit :Para enviar los datos al backend dándole el esquema de validación o resolver de tipo zod

  const onSubmitSignUp = handleSubmit((jsonData) => {
    //Tras ser validado el form con el schema
    signIn({
      emailAddress: jsonData.emailAddress,
      password: jsonData.password,
    }).then((data) => {
      if (!data.status) {
        generalError.value = data.message;
      }
    });
  });

  const onSubmitForgotPassword = (
    event: React.MouseEvent<HTMLButtonElement>
  ) => {
    event.preventDefault();
    const form = event.currentTarget.closest("form");
    if (!form) return;
    const input = form.querySelector("#emailAddress");
    if (!input) return;
    const emailAddress = (input as HTMLInputElement).value;
    forgotPassword({
      emailAddress,
    }).then((data) => {
      if (!data.status) {
        generalError.value = data.message;
      }
    });
  };

  return (
    <div className={styles.signIn}>
      <h2>Iniciar sesión</h2>
      <form className={styles.form} onSubmit={onSubmitSignUp}>
        <div className={styles.inputGroup}>
          {formErrors.emailAddress ? (
            <label htmlFor="emailAddress" className={styles.errorMessage}>
              {formErrors.emailAddress.message}
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
          {formErrors.password ? (
            <label htmlFor="password" className={styles.errorMessage}>
              {formErrors.password.message}
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
          <button type="button" onClick={onSubmitForgotPassword}>
            Contraseña olvidada
          </button>
        </div>
        {generalError && (
          <div className={styles.errorMessage}>
            <p>{generalError}</p>
          </div>
        )}
      </form>
    </div>
  );
}
