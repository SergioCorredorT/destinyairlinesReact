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
    setCountry,
    setTownCity,
    setStreetAddress,
    setZipCode,
    setPhoneNumber1,
    setPhoneNumber2,
    setPhoneNumber3,
    setCompanyName,
    setCompanyTaxNumber,
    setCompanyPhoneNumber,
    setDocumentationType,
    setDocumentCode,
    setExpirationDate,
    setDateBirth,
    setEmailAddress,
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
    setCountry(response.response.userData.country);
    setTownCity(response.response.userData.townCity);
    setStreetAddress(response.response.userData.streetAddress);
    setZipCode(response.response.userData.zipCode);
    setPhoneNumber1(response.response.userData.phoneNumber1);
    setPhoneNumber2(response.response.userData.phoneNumber2);
    setPhoneNumber3(response.response.userData.phoneNumber3);
    setCompanyName(response.response.userData.companyName);
    setCompanyTaxNumber(response.response.userData.companyTaxNumber);
    setCompanyPhoneNumber(response.response.userData.companyPhoneNumber);
    setDocumentationType(response.response.userData.documentationType);
    setDocumentCode(response.response.userData.documentCode);
    setExpirationDate(response.response.userData.expirationDate);
    setDateBirth(response.response.userData.dateBirth);
    setEmailAddress(emailAddress);
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
