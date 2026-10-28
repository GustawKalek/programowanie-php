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

INSERT IGNORE INTO uzytkownicy (imie, nazwisko, email, haslo_hash, rola, klasa) VALUES
('Jakub', 'Adamczyk', 'jakub.adamczyk@zst.test', '$2y$10$xBgLK.3e55/x/iuNZvcKR.2xkDKmggj0P30v5AWzeootah.m/h2ru', 'uczen', '1A'),
('Julia', 'Bąk', 'julia.bak@zst.test', '$2y$10$dBqHTh.Qt7BmWI4.2bSlqO5Rl0Maz4UqN9Kpj685iQxcd5w7Nhco2', 'uczen', '1A'),
('Szymon', 'Czarnecki', 'szymon.czarnecki@zst.test', '$2y$10$HyXbmrVL5gSJPs.LjUwSoeIiQdi1yeRlSLDuirbVV79i/GUYRRurS', 'uczen', '1A'),
('Zuzanna', 'Górska', 'zuzanna.gorska@zst.test', '$2y$10$fIxi/RAwBjTyz4LLHtAMkOgieP/TNtkD0ErstRa4GhWj/oO/906TW', 'uczen', '1A'),
('Filip', 'Jankowski', 'filip.jankowski@zst.test', '$2y$10$ToI8tU0t6Qn2.mCl4m1i.uWi5H43v4cLcxTly8g8fvlkPIGbKekuC', 'uczen', '1A'),
('Maja', 'Krawczyk', 'maja.krawczyk@zst.test', '$2y$10$MoT/WjROnSsgBwjLXZ7jMuEOXz0ZwSC3./a6YA.jG2WfAhRx/sZu.', 'uczen', '1A'),
('Antoni', 'Mazur', 'antoni.mazur@zst.test', '$2y$10$a34iQQj/K9qg9LCpy6Mr4eTQMJSp00dE2zee69vVldoOmHEGuyNUW', 'uczen', '1A'),
('Hanna', 'Nowicka', 'hanna.nowicka@zst.test', '$2y$10$viwUck3woHrqHXznjIx.TuNze.GIsLanZ2ofG6X9isQqdP.6Htzm2', 'uczen', '1A'),
('Jan', 'Pawlak', 'jan.pawlak@zst.test', '$2y$10$Z3cbTpo2TmLrQH.jSLuhb.dDxsfkZ0yyUjP0zJgqZP6GYGXWTpv5.', 'uczen', '1A'),
('Lena', 'Rutkowska', 'lena.rutkowska@zst.test', '$2y$10$utwG9XrPeqkpDE.u6inZsODxut8YtxgUjgU3TaGVK1zP6CwKNjyRq', 'uczen', '1A'),
('Bartosz', 'Kubiak', 'bartosz.kubiak@zst.test', '$2y$10$GdB/NsgnyqRY9Z7Yv1pih.yNx.NBremvNAGIrNTiSyUjsZL2GC.ky', 'uczen', '2A'),
('Wiktoria', 'Michalska', 'wiktoria.michalska@zst.test', '$2y$10$JsXyzKCU92gBs49z2Tn9qe77rEz04orNWQwHei8gk2pka6lCho4Ha', 'uczen', '2A'),
('Kacper', 'Sikora', 'kacper.sikora@zst.test', '$2y$10$IpKtkUYOaFYvnKB4eAFaLuYJQn6Cyw7XRQsQfT0UYn/pVqVAQmksK', 'uczen', '2A'),
('Oliwia', 'Sobczak', 'oliwia.sobczak@zst.test', '$2y$10$RXMXBvGry/PNr3TFX0Oo0One5uuBiLtOfO5Qy9xCpgLeqF289UKBu', 'uczen', '2A'),
('Mikołaj', 'Tomaszewski', 'mikolaj.tomaszewski@zst.test', '$2y$10$OlhflYfq77s0O1bp19g1xuds0BsKS0OAnSR21mmkd/vYfrhTmjNtO', 'uczen', '2A'),
('Amelia', 'Urbańska', 'amelia.urbanska@zst.test', '$2y$10$JICUcIWDnEY3L5cMzQji/.Yjwdl2K7/FDB1aPW4pzsqX5QH7uy2fi', 'uczen', '2A'),
('Wojciech', 'Walczak', 'wojciech.walczak@zst.test', '$2y$10$nXYUsiJ.MBrOheKvWqga.eL.H5vJuC/nFyimCMCarf.PZc29MosWO', 'uczen', '2A'),
('Natalia', 'Wróbel', 'natalia.wrobel@zst.test', '$2y$10$lKAZsgjAeUB3iBgx9uQlHuaLblJrsUHA49ZfhMr2AbWhcb2rsf.6a', 'uczen', '2A'),
('Igor', 'Zając', 'igor.zajac@zst.test', '$2y$10$odfmBwJWLKk/Xd68tFF8sOxOwbB9S5fUgegj4EcuTcwdUmezAgVpq', 'uczen', '2A'),
('Alicja', 'Ziółkowska', 'alicja.ziolkowska@zst.test', '$2y$10$8XuapSjnf3qDvHvZHR2E0ujXTNg40va7rwDnzGpemPfEOK7au8YvK', 'uczen', '2A');

UPDATE przedmioty SET nauczyciel_id = (SELECT id FROM uzytkownicy WHERE email = 'anna.nowak@zst.test') WHERE nazwa = 'Matematyka';
UPDATE przedmioty SET nauczyciel_id = (SELECT id FROM uzytkownicy WHERE email = 'katarzyna.wojcik@zst.test') WHERE nazwa = 'Język polski';
UPDATE przedmioty SET nauczyciel_id = (SELECT id FROM uzytkownicy WHERE email = 'magdalena.lewandowska@zst.test') WHERE nazwa = 'Język angielski';
UPDATE przedmioty SET nauczyciel_id = (SELECT id FROM uzytkownicy WHERE email = 'tomasz.kaminski@zst.test') WHERE nazwa = 'Historia';
UPDATE przedmioty SET nauczyciel_id = (SELECT id FROM uzytkownicy WHERE email = 'piotr.wisniewski@zst.test') WHERE nazwa = 'Fizyka';
UPDATE przedmioty SET nauczyciel_id = (SELECT id FROM uzytkownicy WHERE email = 'agnieszka.szymanska@zst.test') WHERE nazwa = 'Chemia';
UPDATE przedmioty SET nauczyciel_id = (SELECT id FROM uzytkownicy WHERE email = 'ewa.dabrowska@zst.test') WHERE nazwa = 'Biologia';
UPDATE przedmioty SET nauczyciel_id = (SELECT id FROM uzytkownicy WHERE email = 'marek.zielinski@zst.test') WHERE nazwa = 'Geografia';
UPDATE przedmioty SET nauczyciel_id = (SELECT id FROM uzytkownicy WHERE email = 'krzysztof.kozlowski@zst.test') WHERE nazwa = 'Informatyka';
UPDATE przedmioty SET nauczyciel_id = (SELECT id FROM uzytkownicy WHERE email = 'pawel.wozniak@zst.test') WHERE nazwa = 'Wychowanie fizyczne';

INSERT IGNORE INTO uczniowie_przedmioty (uczen_id, przedmiot_id)
SELECT u.id, p.id FROM uzytkownicy u, przedmioty p WHERE u.rola = 'uczen';
