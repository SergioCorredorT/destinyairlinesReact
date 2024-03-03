import { destinyAirlinesFetch } from "./fetchUtils";

export async function googleSignUp({ documentationType, documentCode, expirationDate, firstName, lastName, townCity, streetAddress, zipCode, country, emailAddress, password, phoneNumber1, phoneNumber2, phoneNumber3, companyName, companyTaxNumber, companyPhoneNumber, dateBirth, captchaToken, credentialResponse }) {
    const response = await destinyAirlinesFetch(
        { command: "googleCreateUser", documentationType, documentCode, expirationDate, firstName, lastName, townCity, streetAddress, zipCode, country, emailAddress, password, phoneNumber1, phoneNumber2, phoneNumber3, companyName, companyTaxNumber, companyPhoneNumber, dateBirth, captchaToken, credentialResponse }
    );

    if (response.error) {
        return { status: false, message: "Error de servidor" };
    }
    if (response && response.status && response.response) {
        return { status: true, message: response.message };
    } else {
        return { status: false, message: response.message };
    }
}
