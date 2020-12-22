<template>
    <div>
        <center>Турнир {{ tournamentName }}</center>
        <div id="division_first">
            <p>{{ firstDivisionName }}</p>
            <table id="firstDivisionTable" class="table">
                <thead>
                <tr>
                    <th v-for="teamFirstDivision in this.firstDivisionResults.results">
                        {{ Object.keys(teamFirstDivision)[0] }}
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th v-for="teamFirstDivision in this.firstDivisionResults.results">
                        {{ teamFirstDivision }}
                    </th>
                </tr>
                </tbody>
            </table>
            <button class="btn btn-primary" v-on:click="generateTournament">
                <i class="fas fa-plus"></i>
                Генерация турнира
            </button>
        </div>
    </div>
</template>

<script>
import {generateTournamentsData} from '../services/qualification_service'

export default {
    data() {
        return {
            tournamentId: null,
            tournamentName: null,
            firstDivisionName: null,
            secondDivisionName: null,
            tables: [],
            firstDivisionResults: []
        }
    },
    async mounted() {

    },
    methods: {
        async generateTournament() {
            const generatedTournamentData = await generateTournamentsData()
            console.log(generatedTournamentData)
            this.fillDataAboutQualification(generatedTournamentData)
        },
        fillDataAboutQualification(tournamentResults) {
            this.tables = tournamentResults.tables
            this.tournamentName = tournamentResults.tournament_name;
            this.tournamentId = tournamentResults.tournament_id
            this.firstDivisionResults = tournamentResults.tables[0]
        }
    }
}
</script>
