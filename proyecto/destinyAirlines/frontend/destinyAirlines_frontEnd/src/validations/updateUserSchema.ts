import { z } from "zod";

export const updateUserSchema = z
  .object({
    firstName: z
      .string()
      .max(100, { message: "Nombre muy largo*" })
      .min(2, { message: "Nombre muy corto*" }),
    lastName: z
      .string()
      .max(100, { message: "Apellidos muy largo*" })
      .min(2, { message: "Apellidos muy corto*" }),
    country: z
      .string()
      .max(100, { message: "País muy largo*" })
      .min(2, { message: "País muy corto*" }),
    townCity: z
      .string()
      .max(100, { message: "Ciudad muy largo*" })
      .min(2, { message: "Ciudad muy corto*" }),
    streetAddress: z
      .string()
      .max(100, { message: "Calle muy largo*" })
      .min(2, { message: "Calle muy corto*" }),
    zipCode: z
      .string()
      .max(100, { message: "Código postal muy largo*" })
      .min(2, { message: "Código postal muy corto*" }),
    documentationType: z
      .string()
      .max(200, { message: "Tipo de documento muy largo*" })
      .min(2, { message: "Tipo de documento muy corto*" }),
    documentCode: z
      .string()
      .max(200, { message: "Código de documento muy largo*" })
      .min(2, { message: "Código de documento muy corto*" }),
    expirationDate: z
      .string()
      .refine(
        (dob) =>
          new Date(dob).toString() !== "Invalid Date" &&
          new Date(dob) >= new Date(),
        {
          message: "Fecha de expiración no válida*",
        }
      ),
    title: z
      .string()
      .max(100, { message: "Título muy largo" })
      .min(2, { message: "Título muy corto" })
      .or(z.string().refine((title) => title === "")),
    phoneNumber1: z
      .string()
      .max(20, { message: "Número de teléfono 1 muy largo*" })
      .min(9, { message: "Número de teléfono 1 muy corto*" })
      .regex(/^(\+?[0-9]{9,})$/, { message: "Número de teléfono 1 no válido*" }),
    phoneNumber2: z
      .string()
      .max(20, { message: "Número de teléfono 2 muy largo" })
      .min(9, { message: "Número de teléfono 2 muy corto" })
      .regex(/^(\+?[0-9]{9,})$/, { message: "Número de teléfono 2 no válido" })
      .or(z.string().refine((phoneNumber2) => phoneNumber2 === "")),
    phoneNumber3: z
      .string()
      .max(20, { message: "Número de teléfono 3 muy largo" })
      .min(9, { message: "Número de teléfono 3 muy corto" })
      .regex(/^(\+?[0-9]{9,})$/, { message: "Número de teléfono 3 no válido" })
      .or(z.string().refine((phoneNumber3) => phoneNumber3 === "")),
    companyName: z
      .string()
      .max(100, { message: "Nombre de empresa muy largo" })
      .min(2, { message: "Nombre de empresa muy corto" })
      .or(z.string().refine((companyName) => companyName === "")),
    companyTaxNumber: z
      .string()
      .max(100, { message: "Número de impuesto de empresa muy largo" })
      .min(2, { message: "Número de impuesto de empresa muy corto" })
      .or(z.string().refine((companyTaxNumber) => companyTaxNumber === "")),
    companyPhoneNumber: z
      .string()
      .max(100, { message: "Número de teléfono de empresa muy largo" })
      .min(2, { message: "Número de teléfono de empresa muy corto" })
      .regex(/^(\+?[0-9]{9,})$/, {
        message: "Número de teléfono de empresa no válido",
      })
      .or(z.string().refine((companyPhoneNumber) => companyPhoneNumber === "")),
    dateBirth: z
      .string()
      .refine(
        (dob) =>
          new Date(dob).toString() !== "Invalid Date" &&
          new Date(dob) <= new Date(),
        {
          message: "Fecha de nacimiento no válida*",
        }
      ),
  })
  .refine(
    (data) => {
      let regex;
      switch (data.documentationType) {
        case "dni":
          regex = /^[0-9]{8}[A-Z]$/;
          break;
        case "passport":
          regex = /^[A-Z]{2}[0-9]{6}$/;
          break;
        case "drivers_license":
          regex = /^[A-Z]{1}[0-9]{7}$/;
          break;
        case "residence_card_or_work_permit":
          regex = /^[A-Z]{3}[0-9]{9}$/;
          break;
        default:
          return false;
      }
      return regex.test(data.documentCode);
    },
    { message: "Código de documento no válido*" }
  );
