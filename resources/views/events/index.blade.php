@extends('layouts.app')

@section('content')
<v-container>
    <v-row>
        <v-col cols="12">
            <h1 class="text-h4 mb-4">События в Балаково</h1>
        </v-col>
    </v-row>

    <v-row>
        <v-col cols="12">
            <event-list-component :initial-events='@json($events)'></event-list-component>
        </v-col>
    </v-row>
</v-container>
@endsection