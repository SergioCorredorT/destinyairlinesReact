import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import styles from "./SignUp.module.css";
import { useEffect, useState } from "react";
import { signUpSchema } from "../../validations/signUpSchema";
import { signUp } from "../../services/signUp";
import { useSignal } from "@preact/signals-react";
import { optionsStore } from "../../store/optionsStore";

type Inputs = {
  emailAddress: string;
  password: string;
  confirmPassword: string;
  firstName: string;
  lastName: string;
  country: string;
  townCity: string;
  streetAddress: string;
  zipCode: string;
  documentationType: string;
  documentCode: string;
  expirationDate: string;
  title: string;
  phoneNumber1: string;
  phoneNumber2: string;
  phoneNumber3: string;
  companyName: string;
  companyTaxNumber: string;
  companyPhoneNumber: string;
  dateBirth: string;
};

type optionsForUserRegister = {
  docTypesEs?: { [key: string]: string };
  docTypesAndRegExp?: { [key: string]: string };
  titles?: { [key: string]: string };
  countries?: { [key: string]: string };
};

export function SignUp({ closeModal }: { closeModal: () => void }) {
  const generalError = useSignal("");
  const [optionsForUserRegister, setOptionsForUserRegister] =
    useState<optionsForUserRegister | null>(null);
  const { getOptions } = optionsStore();

  useEffect(() => {
    async function helperOptionsForUserRegister() {
      const response = await getOptions({
        countries: true,
        titles: true,
        docTypesEs: true,
        docTypesAndRegExp: true,
      });
      if (!response) {
        generalError.value = "Could not load document types";
        return;
      } else {
        generalError.value = "";
      }
      setOptionsForUserRegister(response);
    }
    helperOptionsForUserRegister();
  }, []);

  const {
    register,
    handleSubmit,
    formState: { errors: formErrors },
  } = useForm<Inputs>({
    resolver: zodResolver(signUpSchema),
  });

  const onsubmit = handleSubmit((jsonData) => {
    signUp(jsonData).then((data) => {
      if (!data.status) {
        generalError.value = data.message;
      } else {
        generalError.value = "";
        closeModal();
      }
    });
  });

  return (
    <div className={styles.signUp}>
      <h2>Sign up</h2>
      <form className={styles.form} onSubmit={onsubmit}>
        <div className={styles.inputGroupsContainer}>
          <div className={styles.inputGroup}>
            {formErrors.emailAddress ? (
              <label htmlFor="emailAddress" className={styles.errorMessage}>
                {formErrors.emailAddress.message}
              </label>
            ) : (
              <label htmlFor="emailAddress">Email*</label>
            )}
            <input
              type="text"
              id="emailAddress"
              placeholder="juan@dominio.com"
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
              <label htmlFor="password">Password*</label>
            )}
            <input
              type="password"
              id="password"
              placeholder="12345678A"
              title="Introduzca aquí su contraseña"
              {...register("password")}
            />
          </div>
          <div className={styles.inputGroup}>
            {formErrors.confirmPassword ? (
              <label htmlFor="confirmPassword" className={styles.errorMessage}>
                {formErrors.confirmPassword.message}
              </label>
            ) : (
              <label htmlFor="confirmPassword">Repite password*</label>
            )}
            <input
              type="password"
              id="confirmPassword"
              placeholder="12345678A"
              title="Repita aquí su contraseña"
              {...register("confirmPassword")}
            />
          </div>
          <div className={styles.inputGroup}>
            {formErrors.firstName ? (
              <label htmlFor="firstName" className={styles.errorMessage}>
                {formErrors.firstName.message}
              </label>
            ) : (
              <label htmlFor="firstName">Nombre*</label>
            )}
            <input
              type="text"
              id="firstName"
              placeholder="Juan"
              title="Introduzca aquí su nombre"
              {...register("firstName")}
            />
          </div>
          <div className={styles.inputGroup}>
            {formErrors.lastName ? (
              <label htmlFor="lastName" className={styles.errorMessage}>
                {formErrors.lastName.message}
              </label>
            ) : (
              <label htmlFor="lastName">Apellidos*</label>
            )}
            <input
              type="text"
              id="lastName"
              placeholder="Pérez Pérez"
              title="Introduzca aquí su apellido"
              {...register("lastName")}
            />
          </div>
          <div className={styles.inputGroup}>
            {formErrors.country ? (
              <label htmlFor="country" className={styles.errorMessage}>
                {formErrors.country.message}
              </label>
            ) : (
              <label htmlFor="country">País*</label>
            )}
            <select
              defaultValue=""
              id="country"
              title="Introduzca aquí su país"
              {...register("country")}
            >
              <option value="" disabled>
                Seleccione una opción
              </option>
              {optionsForUserRegister &&
                optionsForUserRegister.countries &&
                Object.keys(optionsForUserRegister.countries).map((country) => (
                  <option key={country} value={country}>
                    {country}
                  </option>
                ))}
            </select>
          </div>
          <div className={styles.inputGroup}>
            {formErrors.townCity ? (
              <label htmlFor="townCity" className={styles.errorMessage}>
                {formErrors.townCity.message}
              </label>
            ) : (
              <label htmlFor="townCity">Ciudad*</label>
            )}
            <datalist>
              <option></option>
            </datalist>
            <datalist id="towns">
              <option value="Moscú">Moscú</option>
              <option value="Londres">Londres</option>
              <option value="Berlín">Berlín</option>
              <option value="Madrid">Madrid</option>
              <option value="Kiev">Kiev</option>
              <option value="Roma">Roma</option>
              <option value="Bakú">Bakú</option>
              <option value="París">París</option>
              <option value="Bucarest">Bucarest</option>
              <option value="Minsk">Minsk</option>
              <option value="Viena">Viena</option>
              <option value="Varsovia">Varsovia</option>
              <option value="Budapest">Budapest</option>
              <option value="Belgrado">Belgrado</option>
              <option value="Praga">Praga</option>
              <option value="Sofía">Sofía</option>
              <option value="Tiflis">Tiflis</option>
              <option value="Ereván">Ereván</option>
              <option value="Estocolmo">Estocolmo</option>
              <option value="Ámsterdam">Ámsterdam</option>
            </datalist>
            <input
              list="towns"
              type="text"
              id="townCity"
              placeholder="Albacete"
              title="Introduzca aquí su ciudad"
              {...register("townCity")}
            />
          </div>
          <div className={styles.inputGroup}>
            {formErrors.streetAddress ? (
              <label htmlFor="streetAddress" className={styles.errorMessage}>
                {formErrors.streetAddress.message}
              </label>
            ) : (
              <label htmlFor="streetAddress">Calle*</label>
            )}
            <input
              type="text"
              id="streetAddress"
              placeholder="Calle 123"
              title="Introduzca aquí su calle"
              {...register("streetAddress")}
            />
          </div>
          <div className={styles.inputGroup}>
            {formErrors.zipCode ? (
              <label htmlFor="zipCode" className={styles.errorMessage}>
                {formErrors.zipCode.message}
              </label>
            ) : (
              <label htmlFor="zipCode">Código postal*</label>
            )}
            <input
              type="number"
              id="zipCode"
              placeholder="12345"
              title="Introduzca aquí su código postal"
              {...register("zipCode")}
            />
          </div>
          <div className={styles.inputGroup}>
            {formErrors.documentationType ? (
              <label
                htmlFor="documentationType"
                className={styles.errorMessage}
              >
                {formErrors.documentationType.message}
              </label>
            ) : (
              <label htmlFor="documentationType">Tipo de documento*</label>
            )}
            <select
              id="documentationType"
              title="Introduzca aquí su tipo de documento"
              defaultValue=""
              {...register("documentationType")}
            >
              <option value="" disabled>
                Seleccione una opción
              </option>
              {optionsForUserRegister &&
                optionsForUserRegister.docTypesEs &&
                Object.keys(optionsForUserRegister.docTypesEs).map(
                  (docTypeKey) => (
                    <option key={docTypeKey} value={docTypeKey}>
                      {optionsForUserRegister.docTypesEs &&
                        optionsForUserRegister.docTypesEs[docTypeKey]}
                    </option>
                  )
                )}
            </select>
          </div>
          <div className={styles.inputGroup}>
            {formErrors.documentCode ? (
              <label htmlFor="documentCode" className={styles.errorMessage}>
                {formErrors.documentCode.message}
              </label>
            ) : (
              <label htmlFor="documentCode">Número de documento*</label>
            )}
            <input
              type="text"
              id="documentCode"
              placeholder="123456789A"
              title="Introduzca aquí su número de documento"
              {...register("documentCode")}
            />
          </div>
          <div className={styles.inputGroup}>
            {formErrors.expirationDate ? (
              <label htmlFor="expirationDate" className={styles.errorMessage}>
                {formErrors.expirationDate.message}
              </label>
            ) : (
              <label htmlFor="expirationDate">Fecha de caducidad*</label>
            )}
            <input
              type="date"
              id="expirationDate"
              title="Introduzca aquí su fecha de expiración"
              {...register("expirationDate")}
            />
          </div>
          <div className={styles.inputGroup}>
            {formErrors.title ? (
              <label htmlFor="title" className={styles.errorMessage}>
                {formErrors.title.message}
              </label>
            ) : (
              <label htmlFor="title">Título</label>
            )}
            <select
              id="title"
              title="Introduzca aquí su título"
              defaultValue=""
              {...register("title")}
            >
              <option value="" disabled>
                Seleccione una opción
              </option>
              {optionsForUserRegister &&
                optionsForUserRegister.titles &&
                Object.keys(optionsForUserRegister.titles).map((title) => (
                  <option key={title} value={title}>
                    {optionsForUserRegister.titles &&
                      optionsForUserRegister.titles[title]}
                  </option>
                ))}
            </select>
          </div>
          <div className={styles.inputGroup}>
            {formErrors.phoneNumber1 ? (
              <label htmlFor="phoneNumber1" className={styles.errorMessage}>
                {formErrors.phoneNumber1.message}
              </label>
            ) : (
              <label htmlFor="phoneNumber1">Número de teléfono 1*</label>
            )}
            <input
              type="text"
              id="phoneNumber1"
              placeholder="123456789"
              title="Introduzca aquí su número de teléfono 1"
              {...register("phoneNumber1")}
            />
          </div>
          <div className={styles.inputGroup}>
            {formErrors.phoneNumber2 ? (
              <label htmlFor="phoneNumber2" className={styles.errorMessage}>
                {formErrors.phoneNumber2.message}
              </label>
            ) : (
              <label htmlFor="phoneNumber2">Número de teléfono 2</label>
            )}
            <input
              type="text"
              id="phoneNumber2"
              placeholder="123456789"
              title="Introduzca aquí su número de teléfono 2"
              {...register("phoneNumber2")}
            />
          </div>
          <div className={styles.inputGroup}>
            {formErrors.phoneNumber3 ? (
              <label htmlFor="phoneNumber3" className={styles.errorMessage}>
                {formErrors.phoneNumber3.message}
              </label>
            ) : (
              <label htmlFor="phoneNumber3">Número de teléfono 3</label>
            )}
            <input
              type="text"
              id="phoneNumber3"
              placeholder="123456789"
              title="Introduzca aquí su número de teléfono 3"
              {...register("phoneNumber3")}
            />
          </div>
          <div className={styles.inputGroup}>
            {formErrors.companyName ? (
              <label htmlFor="companyName" className={styles.errorMessage}>
                {formErrors.companyName.message}
              </label>
            ) : (
              <label htmlFor="companyName">Nombre de empresa</label>
            )}
            <input
              type="text"
              id="companyName"
              placeholder="Juan Company"
              title="Introduzca aquí el nombre de su empresa"
              {...register("companyName")}
            />
          </div>
          <div className={styles.inputGroup}>
            {formErrors.companyTaxNumber ? (
              <label htmlFor="companyTaxNumber" className={styles.errorMessage}>
                {formErrors.companyTaxNumber.message}
              </label>
            ) : (
              <label htmlFor="companyTaxNumber">
                Número de identificación fiscal
              </label>
            )}
            <input
              type="text"
              id="companyTaxNumber"
              placeholder="123456789"
              title="Introduzca aquí el número de identificación fiscal de su empresa"
              {...register("companyTaxNumber")}
            />
          </div>
          <div className={styles.inputGroup}>
            {formErrors.companyPhoneNumber ? (
              <label
                htmlFor="companyPhoneNumber"
                className={styles.errorMessage}
              >
                {formErrors.companyPhoneNumber.message}
              </label>
            ) : (
              <label htmlFor="companyPhoneNumber">
                Número de teléfono de su empresa
              </label>
            )}
            <input
              type="text"
              id="companyPhoneNumber"
              placeholder="123456789"
              title="Introduzca aquí el número de teléfono de su empresa"
              {...register("companyPhoneNumber")}
            />
          </div>
          <div className={styles.inputGroup}>
            {formErrors.dateBirth ? (
              <label htmlFor="dateBirth" className={styles.errorMessage}>
                {formErrors.dateBirth.message}
              </label>
            ) : (
              <label htmlFor="dateBirth">Fecha de nacimiento*</label>
            )}
            <input
              type="date"
              id="dateBirth"
              title="Introduzca aquí su fecha de nacimiento"
              {...register("dateBirth")}
            />
          </div>
        </div>
        <div className={styles.buttonsContainer}>
          <button type="submit">Registrarse</button>
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
