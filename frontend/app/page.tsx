"use client";

import { useState } from "react";
import {
  useForm,
  useFieldArray,
  SubmitHandler,
  Controller,
} from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import axios from "axios";


import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Label } from "@/components/ui/label";


const translations = {
  en: {
    title: "Flight Booking Form",
    passengerDetails: "Passenger Details",
    fullName: "Full Name",
    email: "Email",
    bookingReference: "Booking Reference",
    language: "Language",
    flightSegments: "Flight Segments",
    from: "From Airport",
    to: "To Airport",
    departureDate: "Departure Date",
    departureTime: "Departure Time",
    arrivalDate: "Arrival Date",
    arrivalTime: "Arrival Time",
    airline: "Airline",
    operatingAirline: "Operating Airline",
    flightNumber: "Flight Number",
    aircraft: "Aircraft",
    terminal: "Terminal",
    cabin: "Cabin Class",
    baggage: "Checked Baggage",
    carryOn: "Carry-On",
    ticketNumber: "Ticket Number",
    addFlight: "Add Another Flight",
    remove: "Remove",
    submit: "Confirm Booking",
    submitting: "Submitting...",
    success: "Booking Confirmed!",
    successMessage: "Your itinerary has been sent to your email address.",
    anotherBooking: "Book Another Flight",
  },

  de: {
    title: "Flugbuchungsformular",
    passengerDetails: "Passagierdetails",
    fullName: "Vollständiger Name",
    email: "E-Mail",
    bookingReference: "Buchungsreferenz",
    language: "Sprache",
    flightSegments: "Flugabschnitte",
    from: "Abflughafen",
    to: "Zielflughafen",
    departureDate: "Abflugdatum",
    departureTime: "Abflugzeit",
    arrivalDate: "Ankunftsdatum",
    arrivalTime: "Ankunftszeit",
    airline: "Fluggesellschaft",
    operatingAirline: "Durchgeführt von",
    flightNumber: "Flugnummer",
    aircraft: "Flugzeug",
    terminal: "Terminal",
    cabin: "Reiseklasse",
    baggage: "Aufgabegepäck",
    carryOn: "Handgepäck",
    ticketNumber: "Ticketnummer",
    addFlight: "Weiteren Flug hinzufügen",
    remove: "Entfernen",
    submit: "Buchung bestätigen",
    submitting: "Wird gesendet...",
    success: "Buchung bestätigt!",
    successMessage:
      "Ihre Reiseroute wurde an Ihre E-Mail-Adresse gesendet.",
    anotherBooking: "Weitere Buchung",
  },
};



const flightSegmentSchema = z.object({
  from: z
    .string()
    .length(3, "Use 3-letter airport code")
    .transform((val) => val.toUpperCase()),

  to: z
    .string()
    .length(3, "Use 3-letter airport code")
    .transform((val) => val.toUpperCase()),

  departureDate: z.string().min(1, "Departure date required"),

  departureTime: z.string().min(1, "Departure time required"),

  arrivalDate: z.string().min(1, "Arrival date required"),

  arrivalTime: z.string().min(1, "Arrival time required"),

  airline: z.string().min(2, "Airline required"),

  operatingAirline: z.string().optional(),

  flightNumber: z.string().min(2, "Flight number required"),

  aircraft: z.string().min(2, "Aircraft required"),

  terminal: z.string().optional(),

  cabin: z.enum([
    "Economy",
    "Premium Economy",
    "Business",
    "First",
  ]),

  baggage: z.string().min(1, "Baggage required"),

  carryOn: z.string().optional(),

  ticketNumber: z.string().min(5, "Ticket number required"),
});

const bookingSchema = z.object({
  language: z.enum(["en", "de"]),

  passengerName: z.string().min(2, "Passenger name required"),

  passengerEmail: z.string().email("Valid email required"),

  bookingReference: z
    .string()
    .regex(/^[A-Z0-9]{6}$/, "6-character booking reference"),

  flightSegments: z
    .array(flightSegmentSchema)
    .min(1, "At least one flight required"),
});

type BookingFormData = z.infer<typeof bookingSchema>;



export default function BookingForm() {
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [submitSuccess, setSubmitSuccess] = useState(false);

  const {
    register,
    control,
    handleSubmit,
    watch,
    formState: { errors },
    reset,
  } = useForm<BookingFormData>({
    resolver: zodResolver(bookingSchema),

    defaultValues: {
      language: "en",

      passengerName: "",
      passengerEmail: "",
      bookingReference: "",

      flightSegments: [
        {
          from: "",
          to: "",

          departureDate: "",
          departureTime: "",

          arrivalDate: "",
          arrivalTime: "",

          airline: "",
          operatingAirline: "",

          flightNumber: "",

          aircraft: "",
          terminal: "",

          cabin: "Economy",

          baggage: "",
          carryOn: "",

          ticketNumber: "",
        },
      ],
    },
  });

  const language = watch("language");
  const t = translations[language];

  const { fields, append, remove } = useFieldArray({
    control,
    name: "flightSegments",
  });



const onSubmit: SubmitHandler<BookingFormData> = async (data) => {
  setIsSubmitting(true);

  try {
    const response = await axios.post(
      `http://127.0.0.1:8000/api/bookings`,
      data
    );

    if (response.status === 201) {
      setSubmitSuccess(true);
      reset();

      setTimeout(() => {
        setSubmitSuccess(false);
      }, 4000);
    }
  } catch (error: unknown) {

    if (axios.isAxiosError(error)) {

      // Laravel validation error
      if (error.response?.status === 422) {
        const errorDetails = error.response.data;

        console.error("Validation Errors:", errorDetails);

        if (errorDetails.errors) {
          alert(
            "Validation Error: " +
              JSON.stringify(errorDetails.errors)
          );
        } else {
          alert(errorDetails.message || "Validation failed");
        }

      } else {
        alert(error.message || "Axios error occurred");
      }

    } else {
      // Non-Axios error
      console.error(error);
      alert("An unexpected error occurred");
    }

  } finally {
    setIsSubmitting(false);
  }
};

  

  if (submitSuccess) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-green-50 p-4">
        <Card className="w-full max-w-md">
          <CardHeader>
            <CardTitle className="text-green-600">
              ✓ {t.success}
            </CardTitle>
          </CardHeader>

          <CardContent>
            <p>{t.successMessage}</p>

            <Button
              className="w-full mt-4"
              onClick={() => setSubmitSuccess(false)}
            >
              {t.anotherBooking}
            </Button>
          </CardContent>
        </Card>
      </div>
    );
  }

  

  return (
    <div className="min-h-screen bg-gray-50 py-8 px-4">
      <div className="max-w-5xl mx-auto">
        <Card>
          <CardHeader>
            <CardTitle className="text-3xl">
              {t.title}
            </CardTitle>
          </CardHeader>

          <CardContent>
            <form
              onSubmit={handleSubmit(onSubmit)}
              className="space-y-8"
            >
              {/* LANGUAGE */}

              <div className="space-y-2">
                <Label>{t.language}</Label>

                <select
                  {...register("language")}
                  className="w-full border rounded-md p-2"
                >
                  <option value="en">English</option>
                  <option value="de">Deutsch</option>
                </select>
              </div>

              {/* PASSENGER */}

              <div className="space-y-4">
                <h2 className="text-xl font-semibold">
                  {t.passengerDetails}
                </h2>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <Label>{t.fullName}</Label>

                    <Input
                      {...register("passengerName")}
                    />

                    {errors.passengerName && (
                      <p className="text-red-500 text-sm mt-1">
                        {errors.passengerName.message}
                      </p>
                    )}
                  </div>

                  <div>
                    <Label>{t.email}</Label>

                    <Input
                      type="email"
                      {...register("passengerEmail")}
                    />

                    {errors.passengerEmail && (
                      <p className="text-red-500 text-sm mt-1">
                        {errors.passengerEmail.message}
                      </p>
                    )}
                  </div>

                  <div>
                    <Label>{t.bookingReference}</Label>

                    <Input
                      placeholder="ABC123"
                      {...register("bookingReference")}
                    />

                    {errors.bookingReference && (
                      <p className="text-red-500 text-sm mt-1">
                        {errors.bookingReference.message}
                      </p>
                    )}
                  </div>
                </div>
              </div>

              {/* FLIGHT SEGMENTS */}

              <div className="space-y-6">
                <h2 className="text-xl font-semibold">
                  {t.flightSegments}
                </h2>

                {fields.map((field, index) => (
                  <Card
                    key={field.id}
                    className="border p-4"
                  >
                    <div className="flex justify-between items-center mb-6">
                      <h3 className="font-semibold">
                        Flight {index + 1}
                      </h3>

                      {fields.length > 1 && (
                        <Button
                          type="button"
                          variant="destructive"
                          size="sm"
                          onClick={() => remove(index)}
                        >
                          {t.remove}
                        </Button>
                      )}
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                      {/* FROM */}

                      <div>
                        <Label>{t.from}</Label>

                        <Input
                          placeholder="AKL"
                          {...register(
                            `flightSegments.${index}.from`
                          )}
                        />
                      </div>

                      {/* TO */}

                      <div>
                        <Label>{t.to}</Label>

                        <Input
                          placeholder="DXB"
                          {...register(
                            `flightSegments.${index}.to`
                          )}
                        />
                      </div>

                      {/* DEPARTURE DATE */}

                      <div>
                        <Label>{t.departureDate}</Label>

                        <Input
                          type="date"
                          {...register(
                            `flightSegments.${index}.departureDate`
                          )}
                        />
                      </div>

                      {/* DEPARTURE TIME */}

                      <div>
                        <Label>{t.departureTime}</Label>

                        <Input
                          type="time"
                          {...register(
                            `flightSegments.${index}.departureTime`
                          )}
                        />
                      </div>

                      {/* ARRIVAL DATE */}

                      <div>
                        <Label>{t.arrivalDate}</Label>

                        <Input
                          type="date"
                          {...register(
                            `flightSegments.${index}.arrivalDate`
                          )}
                        />
                      </div>

                      {/* ARRIVAL TIME */}

                      <div>
                        <Label>{t.arrivalTime}</Label>

                        <Input
                          type="time"
                          {...register(
                            `flightSegments.${index}.arrivalTime`
                          )}
                        />
                      </div>

                      {/* AIRLINE */}

                      <div>
                        <Label>{t.airline}</Label>

                        <Input
                          {...register(
                            `flightSegments.${index}.airline`
                          )}
                        />
                      </div>

                      {/* OPERATING AIRLINE */}

                      <div>
                        <Label>{t.operatingAirline}</Label>

                        <Input
                          {...register(
                            `flightSegments.${index}.operatingAirline`
                          )}
                        />
                      </div>

                      {/* FLIGHT NUMBER */}

                      <div>
                        <Label>{t.flightNumber}</Label>

                        <Input
                          {...register(
                            `flightSegments.${index}.flightNumber`
                          )}
                        />
                      </div>

                      {/* AIRCRAFT */}

                      <div>
                        <Label>{t.aircraft}</Label>

                        <Input
                          {...register(
                            `flightSegments.${index}.aircraft`
                          )}
                        />
                      </div>

                      {/* TERMINAL */}

                      <div>
                        <Label>{t.terminal}</Label>

                        <Input
                          {...register(
                            `flightSegments.${index}.terminal`
                          )}
                        />
                      </div>

                      {/* CABIN */}

                      <div>
                        <Label>{t.cabin}</Label>

                        <Controller
                          control={control}
                          name={`flightSegments.${index}.cabin`}
                          render={({ field }) => (
                            <Select
                              onValueChange={field.onChange}
                              defaultValue={field.value}
                            >
                              <SelectTrigger>
                                <SelectValue />
                              </SelectTrigger>

                              <SelectContent>
                                <SelectItem value="Economy">
                                  Economy
                                </SelectItem>

                                <SelectItem value="Premium Economy">
                                  Premium Economy
                                </SelectItem>

                                <SelectItem value="Business">
                                  Business
                                </SelectItem>

                                <SelectItem value="First">
                                  First
                                </SelectItem>
                              </SelectContent>
                            </Select>
                          )}
                        />
                      </div>

                      {/* BAGGAGE */}

                      <div>
                        <Label>{t.baggage}</Label>

                        <Input
                          placeholder="35 kg"
                          {...register(
                            `flightSegments.${index}.baggage`
                          )}
                        />
                      </div>

                      {/* CARRY ON */}

                      <div>
                        <Label>{t.carryOn}</Label>

                        <Input
                          placeholder="1 pc"
                          {...register(
                            `flightSegments.${index}.carryOn`
                          )}
                        />
                      </div>

                      {/* TICKET NUMBER */}

                      <div>
                        <Label>{t.ticketNumber}</Label>

                        <Input
                          {...register(
                            `flightSegments.${index}.ticketNumber`
                          )}
                        />
                      </div>
                    </div>
                  </Card>
                ))}

                {/* ADD FLIGHT */}

                <Button
                  type="button"
                  variant="outline"
                  className="w-full"
                  onClick={() =>
                    append({
                      from: "",
                      to: "",

                      departureDate: "",
                      departureTime: "",

                      arrivalDate: "",
                      arrivalTime: "",

                      airline: "",
                      operatingAirline: "",

                      flightNumber: "",

                      aircraft: "",
                      terminal: "",

                      cabin: "Economy",

                      baggage: "",
                      carryOn: "",

                      ticketNumber: "",
                    })
                  }
                >
                  + {t.addFlight}
                </Button>
              </div>

              {/* SUBMIT */}

              <Button
                type="submit"
                className="w-full"
                disabled={isSubmitting}
              >
                {isSubmitting
                  ? t.submitting
                  : t.submit}
              </Button>
            </form>
          </CardContent>
        </Card>
      </div>
    </div>
  );
}


