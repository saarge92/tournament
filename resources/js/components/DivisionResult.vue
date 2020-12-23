<template>
    <div id="division_first">
        <p class="division_name">Дивизион {{ divisionName }}</p>
        <table id="firstDivisionTable" class="table">
            <thead>
            <tr>
                <th>&nbsp;</th>
                <th v-for="firstTeamDivision in divisionTeams">
                    {{ firstTeamDivision.name }}
                </th>
                <th v-if="this.divisionResults.length > 0">Score</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(firstTeamDivisionColumn,index) in divisionTeams">
                <td>
                    {{ firstTeamDivisionColumn.name }}
                </td>
                <td v-for="(firstTeamDivisionRow,indexColumn) in divisionTeams">
                    {{
                        indexColumn == index ? null : divisionResults[index][firstTeamDivisionColumn.name]
                            [firstTeamDivisionRow.name]
                    }}
                </td>
                <td> {{ Object.values(divisionResults[index])[1] }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: ["divisionName", "divisionTeams", "divisionResults"],
    data() {
        return {}
    },
    methods: {
        mapFirstDivisionResults() {
        }
    },
    mounted() {

    },
    watch: {}

}
</script>

<style scoped>
.division_name {
    text-decoration: underline;
    color: red;
    text-align: center;
}
</style>
