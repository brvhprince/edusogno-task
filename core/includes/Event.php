<?php

/**
 * Project: edusogno-task
 * File: Event.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 10/10/2023 at 8:30 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */
class Event
{

    /**
     * This method creates a new event
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool
    {
        $sql = "INSERT INTO ".T_EVENTS." (event_id, name, event_date, attendees) VALUES (?, ?, ?, ?)";

        $eid = Utils::createID('EDE', 'event');
        $name = Utils::test_input(Utils::secureInput($data['name']));
        $date = $data['date'];
        $attendees = null;
        $sendMail = false;
        if (!empty($data['attendees'])) {
            $attendees = implode(',', $data['attendees']);
            $sendMail = true;
        }

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bind_param("ssss", $eid, $name, $date, $attendees);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->affected_rows > 0) {
            if ($sendMail) {
                $this->sendEmailToAttendees($data['attendees'], $name, $date);
            }
            return true;
        }
        return false;
    }

    /**
     * This method deletes an event
     * @param string $event_id
     * @return bool
     */
    public function delete(string $event_id): bool
    {
        $sql = "DELETE FROM ".T_EVENTS." WHERE event_id = ?";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bind_param("s", $event_id);
        $stmt->execute();
        $stmt->store_result();

        return $stmt->affected_rows > 0;
    }

    /**
     * This method updates an event
     * @param array $data
     * @return bool
     */
    public function update(array $data): bool
    {
        $sql = "UPDATE ".T_EVENTS." SET name = ?, event_date = ?, attendees = ? WHERE event_id = ?";

        $name = Utils::test_input(Utils::secureInput($data['name']));
        $date = $data['date'];
        $attendees = null;
        $sendMail = false;
        if (!empty($data['attendees'])) {
            $attendees = implode(',', $data['attendees']);
            $sendMail = true;
        }

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bind_param("ssss", $name, $date, $attendees, $data['token']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->affected_rows > 0) {
            if ($sendMail) {
                $this->sendEmailToAttendees($data['attendees'], $name, $date, false);
            }
            return true;
        }
        return false;
    }



    /**
     * This method fetches all events of a user
     * @param string $user_id
     * @return array
     */
    public function getUserEvents(string $user_id): array
    {
        $sql = "SELECT * FROM ".T_EVENTS." WHERE FIND_IN_SET(?, attendees) ORDER BY id DESC";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];

            while ($row = $result->fetch_assoc()) {

            $entry = [
                'id' => Utils::encode($row['event_id']),
                'name' => Utils::test_output($row['name']),
                'date' => date_create($row['event_date'])->format('D, d M Y \a\t h:i A'),
            ];

                Utils::getTimeStamps(EdusognoApp::config('date_style'), $row, $entry);

            $data[] = $entry;
            }

            return $data;

    }

    /**
     * This method fetches event by event_id
     * @param string $event_id
     * @return array
     */
    public function getEvent(string $event_id): array
    {
        $sql = "SELECT * FROM ".T_EVENTS." WHERE event_id = ?";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bind_param("s", $event_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];

        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();

            $entry = [
                'id' => Utils::encode($row['event_id']),
                'name' => Utils::test_output($row['name']),
                'date' => date_create($row['event_date'])->format('Y-m-d\TH:i'),
                'attendees' => !empty($row['attendees']) ? explode(',', $row['attendees']) : [],
            ];

            Utils::getTimeStamps(EdusognoApp::config('date_style'), $row, $entry);

            $data = $entry;
        }

        return $data;

    }

    /**
     * This method fetches all events
     * @return array
     */
    public function getAllEvents(): array
    {
        $sql = "SELECT * FROM ".T_EVENTS." ORDER BY id DESC";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];

            while ($row = $result->fetch_assoc()) {

            $entry = [
                'id' => Utils::encode($row['event_id']),
                'name' => Utils::test_output($row['name']),
                'date' => date_create($row['event_date'])->format('D, d M Y \a\t h:i A'),
                'attendees' => !empty($row['attendees']) ? explode(',', $row['attendees']) : [],
            ];

                Utils::getTimeStamps(EdusognoApp::config('date_style'), $row, $entry);

            $data[] = $entry;
            }

            return $data;

    }

    /**
     * This method adds a user to an event
     * @param string $user_id
     * @param string $event_id
     * @return bool
     */
    public function addAttendee(string $user_id, string $event_id): bool
    {
        $sql = "SELECT * FROM ".T_EVENTS." WHERE event_id = ?";

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bind_param("s", $event_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();

            $attendees = $row['attendees'];

            if (empty($attendees)) {
                $attendees = $user_id;
            }
            else {
                $attendees .= ",".$user_id;
            }

            $sql = "UPDATE ".T_EVENTS." SET attendees = ? WHERE event_id = ?";

            $stmt = Database::getConnection()->prepare($sql);
            $stmt->bind_param("ss", $attendees, $event_id);
            $stmt->execute();

            return true;
        }

        return false;
    }

    /**
     * This method returns total events as an integer
     * @return int
     */
    public function totalEvents(): int
    {
        $sql = "SELECT * FROM ".T_EVENTS;
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows;
    }

    /**
     * This method sends an email to attendees on create or event updates
     * @param array $attendees
     * @param string $event_name
     * @param string $event_date
     * @param bool $new
     * @todo this method is best suited for cron jobs. It should be moved to a cron job
     * @return void
     */
    public function sendEmailToAttendees(array $attendees, string $event_name, string $event_date, bool $new = true): void
    {
        if ($new) {
            $template = Utils::fetchTemplate('mail/event-added');
        }
        else {
            $template = Utils::fetchTemplate('mail/event-updated');
        }

        $vars = [
            'site_name' => EdusognoApp::config('site_name'),
            'event_name' => $event_name,
            'date' => date('Y'),
            'event_date' => date_create($event_date)->format('d M Y \a\t h:i A')
        ];


        $userController = new User();
        $mailer = new Mailing();

        if ($new) {
            $subject =  EdusognoApp::config('site_name') . " - New Event Added";
        }
        else {
            $subject =  EdusognoApp::config('site_name') . " - Event Updated";
        }

        foreach ($attendees as $attendee) {
            $user = $userController->fetchUser($attendee);
            $vars['name'] = $user['first_name'];

            $message = Utils::parseTemplate($template, $vars);

            // fallback in case template couldn't be loaded and parsed
            if (empty($message)) {
                if ($new) {
                    $message ="Hello {$user['first_name']}, an event has been added. Event name: $event_name, Event date: $event_date";
                }
                else {
                    $message ="Hello {$user['first_name']}, an event has been updated. Event name: $event_name, Event date: $event_date";
                }
            }
            $mailer->setPayload($user['first_name'], $user['email'], $subject, $message);
            $mailer->send();
        }

    }

}

