import { z } from "zod";

export const removeUserSchema = z
  .object({
    password: z
      .string()
      .min(8, { message: "Password mínimo 8 caracteres*" })
      .max(100, { message: "Password muy largo*" }),
  })
