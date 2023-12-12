BEGIN
  DECLARE total INT;
  DECLARE flight_date DATE;
  SET total = OLD.adultsNumber + OLD.childsNumber + OLD.infantsNumber;
  SELECT date INTO flight_date FROM flights WHERE id_FLIGHTS = OLD.id_FLIGHTS;
  IF flight_date > CURDATE() THEN
    UPDATE flights SET freeSeats = freeSeats + total WHERE id_FLIGHTS = OLD.id_FLIGHTS;
  END IF;
END