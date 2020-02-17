<div class="container-fluid-lg pt-3">
    <div class="row mt-3 mx-0 font-weight-bold d-none d-md-flex">
        <div class="col">Datum</div>
        <div class="col">Module</div>
        @foreach($sensors as $sensor)
            <div class="col text-break text-center">{{ $sensor->name_alias }}</div>
        @endforeach
    </div>
    <div>
        @foreach($table_data as $date => $record)
            @foreach($record as $module_id => $values)
                <hr class="my-0">
                <div class="row py-3 mx-0 record">
                    <div class="col-md col-6">
                        {{ date('d M y H:i', strtotime($date)) }}
                    </div>
                    <div class="col-md col-6">
                        {{ $module_id }}
                    </div>
                @php
                    //Sort on sensor_id (id = id from sensors table)
                        usort($values, function($a, $b) {
                          return $a->module_sensor_id - $b->module_sensor_id;
                        });
                @endphp

                <!--Place every measurement in the right column according to its sensor type-->
                    @php $notInTableCount = 0 //counter to get the number of sensors that don't have a value in order to get the right key in the values array @endphp
                    @for($i=0; $i<count($sensors); $i++)
                        <div class="col-md col-auto text-md-center">
                            @if (array_key_exists($i-$notInTableCount, $values) && $values[$i-$notInTableCount]->name == $sensors[$i]->name)
                                @if ($values[$i-$notInTableCount]->name == "Dendrometer")
                                @foreach($sensor_added_values as $sensor_added_value)
                                    {{ $sensor_added_value->module_sensor_id == $values[$i-$notInTableCount]->module_sensor_id ? $values[$i-$notInTableCount]->value + $sensor_added_value->value . ' ' . $values[$i-$notInTableCount]->measuring_unit . ' (' . $values[$i-$notInTableCount]->value . ' ' . $values[$i-$notInTableCount]->measuring_unit . ' groei)' : '' }}
                                @endforeach
                                @else
                                    {{ $values[$i-$notInTableCount]->value ?? '' }} {{ $values[$i-$notInTableCount]->measuring_unit ?? '' }}
                                @endif
                                @php $notInTableCount = 0 @endphp
                            @else
                                @php $notInTableCount += 1; @endphp
                            @endif
                        </div>
                    @endfor
                </div>
            @endforeach
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $table_data->links() }}
    </div>
</div>



