import { toast } from "react-toastify";
import { destinyAirlinesFetch } from "./fetchUtils";

export async function signIn({ emailAddress, password, get }) {
  const response = await destinyAirlinesFetch(
    { emailAddress, password, command: "loginUser" }
  );
  const {
    setAccessToken,
    setRefreshToken,
    setTitle,
    setFirstName,
    setLastName,
    setIsLoggedIn,
  } = get();

  if (response.error) {
    toast.error(`Error en la petición a servidor: ${response.error}`);
    return { status: false, message: "Error de servidor" };
  }

  if (response && response.status && response.response) {
    setAccessToken(response.tokens.accessToken);
    setRefreshToken(response.tokens.refreshToken);
    setTitle(response.response.userData.title);
    setFirstName(response.response.userData.firstName);
    setLastName(response.response.userData.lastName);
    setIsLoggedIn(true);
    toast.success("Se ha iniciado sesión");
    return { status: true, message: "Se ha iniciado sesión" };
  } else {
    toast.error(
      "Correo electrónico o contraseña incorrectos. Tras 5 intentos fallidos, tu cuenta se bloqueará y recibirás un correo de recuperación"
    );
    return { status: false, message: "La autenticación ha fallado" };
  }
}
