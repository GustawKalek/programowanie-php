SET NAMES utf8mb4;
USE dziennik;

INSERT IGNORE INTO uzytkownicy (imie, nazwisko, email, haslo_hash, rola) VALUES
('Admin', 'Admin', 'admin', '$2y$10$qHK2nvrPg0ELIFNlAxTYKeaKMAbJInBm8XmUx3eGSSxU6rVr0mS26', 'admin'),
('Anna', 'Nowak', 'anna.nowak@zst.test', '$2y$10$6BDUy4ZVF0s5YX9mIZrY1eLQP/2YAxTf/luXJOs1oWbzpGC9CCO.y', 'nauczyciel'),
('Piotr', 'Wiśniewski', 'piotr.wisniewski@zst.test', '$2y$10$TPVsRf0pd7xTex5Nyn5k7.uPepm5JQBOQP8zIzZLXrbqFSQCcYRQa', 'nauczyciel'),
('Katarzyna', 'Wójcik', 'katarzyna.wojcik@zst.test', '$2y$10$rPernxdOMBWgBHdqlUAvH.CQDpsniyH9OzJ0RZO5xujoADWifZcSa', 'nauczyciel'),
('Tomasz', 'Kamiński', 'tomasz.kaminski@zst.test', '$2y$10$9c9eIpencGTAKNTPtGXuE.a/JLzeqXg0nqQZSLHRvtwyW85CvzLcW', 'nauczyciel'),
('Magdalena', 'Lewandowska', 'magdalena.lewandowska@zst.test', '$2y$10$XDw8fX9Ktla4zqJ9JXWEN.c692.tvRasFw9yJ8uUVesxDHXXmmyZO', 'nauczyciel'),
('Marek', 'Zieliński', 'marek.zielinski@zst.test', '$2y$10$Og49TwPbGjkiGvzrYhakeO3anI.I6.U7UpjqeplRsyrQxtpZ/Nk5e', 'nauczyciel'),
('Agnieszka', 'Szymańska', 'agnieszka.szymanska@zst.test', '$2y$10$.jSLLC3DFW/GjCEAbVry0OwJmzT0MxPvfLq.KzHQc1kPZl3eR99RS', 'nauczyciel'),
('Paweł', 'Woźniak', 'pawel.wozniak@zst.test', '$2y$10$Bktjf5FOFJdbEJ42Z1cGf.hhl4btT7z6FhP33LrLs6Nw4XuWnOvFO', 'nauczyciel'),
('Ewa', 'Dąbrowska', 'ewa.dabrowska@zst.test', '$2y$10$UzBdoLSBC.kdanQjvI2FWOJBFBdNN6rmPHzTr81BWP0feCA7WiJ7G', 'nauczyciel'),
('Krzysztof', 'Kozłowski', 'krzysztof.kozlowski@zst.test', '$2y$10$cdiXha94oLlCyM.ish8cHuDvRK7zG3NsqMyaIH9biw.FxA5WVbSb6', 'nauczyciel');

INSERT IGNORE INTO przedmioty (nazwa) VALUES
('Matematyka'),
('Język polski'),
('Język angielski'),
('Historia'),
('Fizyka'),
('Chemia'),
('Biologia'),
('Geografia'),
('Informatyka'),
('Wychowanie fizyczne');
