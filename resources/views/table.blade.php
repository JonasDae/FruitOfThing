{{--
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
--}}


<div class="container-fluid-lg pt-3">
    <div class="row mt-3 mx-0 font-weight-bold d-none d-md-flex">
        <div class="col-lg-auto col-2 mr-lg-5">Datum</div>
        <div class="col-lg-auto col-1 ml-lg-5">Module</div>
        @foreach($sensors as $sensor)
            <div class="col-lg-2 col-md-2">{{ $sensor->name_alias }}</div>
        @endforeach
    </div>
    <div>
        @foreach($table_data as $date => $record)
            @foreach($record as $module_id => $values)
                <div class="row py-3 mx-0">
                    <div class="col-auto">
                        {{ $date}}
                    </div>
                    <div class="col-auto">
                        {{ $module_id }}
                    </div>
                    @php
                        usort($values, function($a, $b) {
                            return $a['module_sensor_id'] - $b['module_sensor_id'];
                        });
                    @endphp
                    @foreach($values as $measurement)
                        <div class="col-lg-2 col-md-2 col-2">
                            @if ($measurement->module_sensor->sensor->name == "Dendrometer")
                                @foreach($sensor_added_values as $sensor_added_value)
                                    {{ $sensor_added_value->module_sensor_id == $measurement->module_sensor_id ? $measurement->value + $sensor_added_value->value . ' ' . $measurement->module_sensor->sensor->measuring_unit . ' (' . $measurement->value . ' ' . $measurement->module_sensor->sensor->measuring_unit . ' groei)' : '' }}
                                @endforeach
                            @else
                                {{ $measurement->value ?? '' }} {{ $measurement->module_sensor->sensor->measuring_unit ?? '' }}
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endforeach
    </div>

