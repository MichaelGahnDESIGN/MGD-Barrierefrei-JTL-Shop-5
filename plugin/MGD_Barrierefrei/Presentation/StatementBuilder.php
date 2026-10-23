<?php

declare(strict_types=1);

namespace Plugin\MGD_Barrierefrei\Presentation;

/** Erstellt kopierbaren Klartext statt ungeprüften HTML-Inhalt in einem CMS zu speichern. */
final class StatementBuilder
{
    /** @param array<string,string> $values */
    public function build(array $values): string
    {
        $organisation = $this->line($values['organisation'] ?? '', 'Ihr Unternehmen');
        $responsible = $this->line($values['responsible_name'] ?? '', 'der zuständigen Stelle');
        $email = $this->line($values['contact_email'] ?? '', '');
        $barriers = $this->line($values['known_barriers'] ?? '', 'Uns sind derzeit keine konkreten Barrieren bekannt. Diese Angabe bitte vor Veröffentlichung fachlich prüfen und bei Änderungen aktualisieren.');
        $status = match ($values['statement_status'] ?? 'teilweise') {
            'vollstaendig' => 'vollständig barrierefrei',
            'nicht' => 'noch nicht vollständig barrierefrei',
            default => 'teilweise barrierefrei',
        };
        $contact = $email === '' ? $responsible : $responsible . ', E-Mail: ' . $email;

        return "Erklärung zur Barrierefreiheit\n\n"
            . "Diese Erklärung zur Barrierefreiheit gilt für {$organisation}.\n\n"
            . "Stand der Vereinbarkeit mit den Anforderungen\n\n"
            . "Diese Website ist nach eigener Einschätzung {$status}. Wir arbeiten daran, Inhalte und Funktionen verständlich und zugänglich zu gestalten.\n\n"
            . "Nicht barrierefreie Inhalte\n\n{$barriers}\n\n"
            . "Feedback und Kontakt\n\nWenn Ihnen Barrieren auffallen oder Sie Hilfe bei der Nutzung benötigen, wenden Sie sich bitte an {$contact}.\n\n"
            . "Hinweis: Dieser Text ist eine technisch erzeugte Vorlage und keine Rechtsberatung. Angaben, Zuständigkeiten und rechtliche Anforderungen müssen vor der Veröffentlichung geprüft und aktuell gehalten werden.";
    }

    private function line(string $value, string $fallback): string
    {
        $value = trim(preg_replace('/\s+/u', ' ', $value) ?? '');
        return $value === '' ? $fallback : mb_substr($value, 0, 2_000);
    }
}
