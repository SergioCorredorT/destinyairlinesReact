import { destinyAirlinesFetch } from "./fetchUtils";

export async function googleSignUp({getAll, documentationType, documentCode, expirationDate, firstName, lastName, townCity, streetAddress, zipCode, country, emailAddress, password, phoneNumber1, phoneNumber2, phoneNumber3, companyName, companyTaxNumber, companyPhoneNumber, dateBirth, captchaToken, credentialResponse }) {
    const response = await destinyAirlinesFetch(
        { command: "googleCreateUser", documentationType, documentCode, expirationDate, firstName, lastName, townCity, streetAddress, zipCode, country, emailAddress, password, phoneNumber1, phoneNumber2, phoneNumber3, companyName, companyTaxNumber, companyPhoneNumber, dateBirth, captchaToken, credentialResponse }
    );

    if(!response) {
        return { status: false, message: "Error de servidor" };
    }

    if (!(response.status) || !(response.response)) {
        if (response.error) {
            return { status: false, message: response.error };
        }
        if (response.message) {
            return { status: false, message: response.message };
        }
        return { status: false, message: "Error de servidor" };
    }

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
        activateAutoUpdateToken
      } = getAll();

    setAccessToken(response.tokens.accessToken);
    setRefreshToken(response.tokens.refreshToken || "");
    setTitle(response.response.userData.title || "");
    setFirstName(response.response.userData.firstName || "");
    setLastName(response.response.userData.lastName || "");
    setCountry(response.response.userData.country || "");
    setTownCity(response.response.userData.townCity || "");
    setStreetAddress(response.response.userData.streetAddress || "");
    setZipCode(response.response.userData.zipCode || "");
    setPhoneNumber1(response.response.userData.phoneNumber1 || "");
    setPhoneNumber2(response.response.userData.phoneNumber2 || "");
    setPhoneNumber3(response.response.userData.phoneNumber3 || "");
    setCompanyName(response.response.userData.companyName || "");
    setCompanyTaxNumber(response.response.userData.companyTaxNumber || "");
    setCompanyPhoneNumber(response.response.userData.companyPhoneNumber || "");
    setDocumentationType(response.response.userData.documentationType || "");
    setDocumentCode(response.response.userData.documentCode || "");
    setExpirationDate(response.response.userData.expirationDate || "");
    setDateBirth(response.response.userData.dateBirth || "");
    setEmailAddress(response.response.userData.emailAddress || "");
    setIsLoggedIn(true);
    activateAutoUpdateToken();
    return { status: true, message: response.message };
}
