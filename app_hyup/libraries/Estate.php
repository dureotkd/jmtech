<?php
class estate
{

    public function __construct()
    {

        $this->obj = &get_instance();
    }

    public function convert($estate_row)
    {
        $data = $estate_row;

        $images = [];
        $pre_file_id = $data['pre_file_id'];

        if (!empty($pre_file_id)) {

            $main_image = $this->obj->service_model->get_file('row', [
                "target_type = 'estate'",
                "id = '{$pre_file_id}'",
            ]);
        }

        if (!empty($file_ids)) {

            $sub_images = $this->service_model->get_file('all', [
                "target_type = 'estate'",
                "id IN (" . implode(',', $file_ids) . ")",
            ]);

            array_push($images, ...$sub_images);
        }

        $start_text = get_day_text($data['start_date']);
        $end_text = get_day_text($data['end_date']);

        $im_start_text = get_day_text($data['im_start_date']);
        $im_end_text = get_day_text($data['im_end_date']);

        $data['main_image'] = $main_image ?? null;
        $data['images'] = $images;

        $data['start_text'] = $start_text;
        $data['end_text'] = $end_text;
        $data['im_start_text'] = $im_start_text;
        $data['im_end_text'] = $im_end_text;
        $data['rent_array'] = rent_array($data['rent_day']);

        // $data['guest'] = json_decode($data['guest_json'], true);
        // $check_in_date_txt = get_day_text($data['check_in_date']);
        // $check_out_date_txt = get_day_text($data['check_out_date']);
        // $guest_json_txt = get_guest_txt($data['guest']);

        // $data['check_in_date_txt'] = $check_in_date_txt;
        // $data['check_out_date_txt'] = $check_out_date_txt;
        // $data['guest_json_txt'] = $guest_json_txt;

        return $data;
    }

    public function convertToReservation($estate_reservation_all)
    {
        $estate_reservation_all_data = [];

        if (!empty($estate_reservation_all)) {

            foreach ($estate_reservation_all as $row) {

                $status = !empty($row['reservation_status']) ? $row['reservation_status'] : $row['status'];
                $pre_file_id = $row['pre_file_id'];

                if (!empty($pre_file_id)) {

                    $main_image = $this->obj->service_model->get_file('row', [
                        "target_type = 'estate'",
                        "id = '{$pre_file_id}'",
                    ]);

                    $row['main_image'] = $main_image;
                }

                $row['guest'] = json_decode($row['guest_json'], true);

                $check_in_date_txt = get_day_text($row['check_in_date']);
                $check_out_date_txt = get_day_text($row['check_out_date']);
                $guest_json_txt = get_guest_txt($row['guest']);

                $row['check_in_date_txt'] = $check_in_date_txt;
                $row['check_out_date_txt'] = $check_out_date_txt;
                $row['guest_json_txt'] = $guest_json_txt;

                $estate_reservation_all_data[$status][] = $row;
            }
        }

        return $estate_reservation_all_data;
    }

    public function convertToReservationRow($row)
    {

        if (!empty($row)) {

            $pre_file_id = $row['pre_file_id'];

            if (!empty($pre_file_id)) {

                $main_image = $this->obj->service_model->get_file('row', [
                    "target_type = 'estate'",
                    "id = '{$pre_file_id}'",
                ]);

                $row['main_image'] = $main_image;
            }

            $row['guest'] = json_decode($row['guest_json'], true);

            $check_in_date_txt = get_day_text($row['check_in_date']);
            $check_out_date_txt = get_day_text($row['check_out_date']);
            $guest_json_txt = get_guest_txt($row['guest']);
            $stay_days = get_stay_days($row['check_in_date'], $row['check_out_date']);

            $row['check_in_date_txt'] = $check_in_date_txt;
            $row['check_out_date_txt'] = $check_out_date_txt;
            $row['guest_json_txt'] = $guest_json_txt;
            $row['stay_days'] = $stay_days;
        }

        return $row;
    }
}
