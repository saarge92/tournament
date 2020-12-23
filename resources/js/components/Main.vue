<template>
    <div>
        <center>Турнир {{ tournamentName }}</center>
        <first-division-info :division-name="firstDivisionName"
                             :division-results="firstDivisionResults"
                             :division-teams="firstDivisionTeams"
        ></first-division-info>
        <second-division-info :division-name="secondDivisionName"
                              :division-results="secondDivisionResults"
                              :division-teams="secondDivisionTeams"
        ></second-division-info>
        <button class="btn btn-primary" v-on:click="generateTournament">
            <i class="fas fa-plus"></i>
            Генерация турнира
        </button>
    </div>
</template>

<script>
import {generateTournamentsData, getTeamsByDivision} from '../services/qualification_service'
import DivisionResult from "./DivisionResult";

export default {
    data() {
        return {
            tournamentId: null,
            tournamentName: null,
            firstDivisionName: null,
            secondDivisionName: null,
            tables: [],
            firstDivisionResults: [],
            firstDivisionTeams: [],
            secondDivisionTeams: [],
            secondDivisionResults: []
        }
    },
    async mounted() {

    },
    methods: {
        async generateTournament() {
            const generatedTournamentData = await generateTournamentsData()
            console.log(generatedTournamentData)
            await this.fillDataAboutQualification(generatedTournamentData)
        },
        async fillDataAboutQualification(tournamentResults) {
            this.tables = tournamentResults.tables
            this.tournamentName = tournamentResults.tournament_name;
            this.tournamentId = tournamentResults.tournament_id
            this.firstDivisionResults = tournamentResults.tables[0].results
            this.firstDivisionName = tournamentResults.tables[0].division_name
            this.firstDivisionTeams = await getTeamsByDivision(tournamentResults.tables[0].division_id)

            this.secondDivisionName = tournamentResults.tables[1].division_name
            this.secondDivisionTeams = await getTeamsByDivision(tournamentResults.tables[1].division_id)
            this.secondDivisionResults = tournamentResults.tables[1].results;

        }
    },
    components: {
        'first-division-info': DivisionResult,
        'second-division-info': DivisionResult
    }
}
</script>
