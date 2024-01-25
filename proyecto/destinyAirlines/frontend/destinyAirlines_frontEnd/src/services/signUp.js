import { toast } from "react-toastify";
import { destinyAirlinesFetch } from "./fetchUtils";

export async function signUp({ documentationType, documentCode, expirationDate, firstName, lastName, townCity, streetAddress, zipCode, country, emailAddress, password, phoneNumber1, phoneNumber2, phoneNumber3, companyName, companyTaxNumber, companyPhoneNumber, dateBirth }) {
    const response = await destinyAirlinesFetch(
        { documentationType, documentCode, expirationDate, firstName, lastName, townCity, streetAddress, zipCode, country, emailAddress, password, phoneNumber1, phoneNumber2, phoneNumber3, companyName, companyTaxNumber, companyPhoneNumber, dateBirth, command: "createUser" }
    );

    if (response.error) {
        toast.error(`Error en la petición a servidor: ${response.error}`);
        return { status: false, message: "Error de servidor" };
    }
    if (response && response.status && response.response) {
        toast.success("Se ha registrado la cuenta con éxito");
        return { status: true, message: "Se ha registrado la cuenta con éxito" };
    } else {
        toast.error(
            "Hubo un error al registrar la cuenta. Por favor, inténtelo de nuevo"
        );
        return { status: false, message: "Hubo un error al registrar la cuenta. Por favor, inténtelo de nuevo" };
    }
}
