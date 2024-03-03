import styles from "./SignIn.module.css";
import { authStore } from "../../store/authStore";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { signInSchema } from "../../validations/signInSchema";
import { useSignal } from "@preact/signals-react";
import { forgotPassword } from "../../services/forgotPassword";
import { toast } from "react-toastify";
import { GoogleLogin } from "@react-oauth/google";

type Inputs = {
  emailAddress: string;
  password: string;
};

interface credentialResponse {
  clientId?: string;
  credential?: string;
  select_by?: string;
}

export function SignIn() {
  const generalError = useSignal("");
  //const [generalError, setGeneralError] = useState<string | null>(null);
  const { signInStore, googleSignInStore } = authStore();
  const {
    register,
    handleSubmit,
    formState: { errors: formErrors },
    getValues,
    setValue,
  } = useForm<Inputs>({
    resolver: zodResolver(signInSchema),
  });
  //Register :Para señalar los inputs a tener en cuenta en react-hook-form
  //handleSubmit :Para enviar los datos al backend dándole el esquema de validación o resolver de tipo zod

  const onSubmitSignIn = handleSubmit(async (jsonData) => {
    //Tras ser validado el form con el schema
    try {
      const data = await signInStore({
        emailAddress: jsonData.emailAddress,
        password: jsonData.password,
      });
      if (!data.status) {
        generalError.value = data.message;
      }
    } catch (error: any) {
      toast.error(error);
    }
  });

  const onSubmitForgotPassword = () => {
    setValue("password", "_Aa1234567");
    const emailAddress = getValues("emailAddress");

    handleSubmit(
      () => {
        setValue("password", "");
        forgotPassword({
          emailAddress,
        }).then((data) => {
          if (!data.status) {
            generalError.value = data.message;
          }
        });
      },
      () => {
        setValue("password", "");
      }
    )();
  };

  const googleSubmit = async (credentialResponse: credentialResponse) => {
    try {
      if (!credentialResponse.credential) {
        generalError.value = "Error en la credencial";
        return;
      }
      const data = await googleSignInStore({
        credential: credentialResponse.credential,
      });
      if (!data.status) {
        generalError.value = data.message;
      }
    } catch (error: any) {
      toast.error(error);
    }
  };

  return (
    <div className={styles.signIn}>
      <h2>Iniciar sesión</h2>
      <form className={styles.form} onSubmit={onSubmitSignIn}>
        <div className={styles.inputGroupsContainer}>
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
        </div>
        <div className={styles.buttonsContainer}>
          <button type="submit">Iniciar sesión</button>
          <button type="button" onClick={onSubmitForgotPassword}>
            Contraseña olvidada
          </button>
          <div className={styles.googleButton}>
            <GoogleLogin
              auto_select
              onSuccess={(credentialResponse) => {
                googleSubmit(credentialResponse);
              }}
              onError={() => {
                generalError.value =
                  "Ocurrió un error durante la autenticación con Google";
              }}
            />
          </div>
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
