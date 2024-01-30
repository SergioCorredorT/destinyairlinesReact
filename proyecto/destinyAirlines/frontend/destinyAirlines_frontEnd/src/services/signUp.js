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
        toast.success(response.message);
        return { status: true, message: response.message };
    } else {
        toast.error(
            response.message
        );
        return { status: false, message: response.message };
    }
}
