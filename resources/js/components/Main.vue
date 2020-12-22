<template>
    <div>
        <center>Турнир {{ tournamentName }}</center>
        <div id="division_first">
            <p>Дивизион {{ firstDivisionName }}</p>
            <table id="firstDivisionTable" class="table">
                <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th v-for="firstTeamDivision in firstDivisionTeams">
                        {{ firstTeamDivision.name }}
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="firstTeamDivision in firstDivisionTeams">
                    <td>
                        {{ firstTeamDivision.name }}
                    </td>
                    <td v-for="resultMatch of firstDivisionResults">
                        {{ resultMatch }}
                    </td>
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
import {generateTournamentsData, getTeamsByDivision} from '../services/qualification_service'

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

            const firstResultexample = this.firstDivisionResults[0]['Россия']
            console.log('Результат первого дивизиона', firstResultexample)
        }
    }
}
</script>
