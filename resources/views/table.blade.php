<table class="table" id="data_table">
    <thead>
    <tr>
        <th scope="col" width=auto> Datum</th>
        <th scope="col"> Module</th>
        @foreach($sensors as $sensor)
            <th scope="col">{{ $sensor->name_alias }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody id="data_table_body">

    @foreach($table_data as $date => $record)
        @foreach($record as $module_id => $values)
            <tr>
                <td>{{ $date }}</td>
                <td>{{ $module_id }}</td>
                @php
                    usort($values, function($a, $b) {
                        return $a['module_sensor_id'] - $b['module_sensor_id'];
                    });
                @endphp
                @foreach($values as $measurement)
                    <td>
                        @if ($measurement->module_sensor->sensor->name == "Dendrometer")
                            @foreach($sensor_added_values as $sensor_added_value)
                                {{ $sensor_added_value->module_sensor_id == $measurement->module_sensor_id ? $measurement->value + $sensor_added_value->value . ' ' . $measurement->module_sensor->sensor->measuring_unit . ' (' . $measurement->value . ' ' . $measurement->module_sensor->sensor->measuring_unit . ' groei)' : '' }}
                            @endforeach
                        @else
                            {{ $measurement->value ?? '' }} {{ $measurement->module_sensor->sensor->measuring_unit ?? '' }}
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
