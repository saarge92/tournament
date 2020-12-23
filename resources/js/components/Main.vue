<template>
    <div>
        <center>Турнир {{ tournamentName }}</center>
        <first-division :first-division-name="firstDivisionName"
                        :first-division-results="firstDivisionResults"
                        :first-division-teams="firstDivisionTeams"
        ></first-division>
        <button class="btn btn-primary" v-on:click="generateTournament">
            <i class="fas fa-plus"></i>
            Генерация турнира
        </button>
    </div>
</template>

<script>
import {generateTournamentsData, getTeamsByDivision} from '../services/qualification_service'
import FirstDivisionTable from "./FirstDivisionTable";

export default {
    data() {
        return {
            tournamentId: null,
            tournamentName: null,
            firstDivisionName: null,
            secondDivisionName: null,
            tables: [],
            firstDivisionResults: [],
            firstDivisionTeams: []
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
        }
    },
    components: {
        'first-division': FirstDivisionTable
    }
}
</script>
