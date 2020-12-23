export async function generatePlayOff(idTournament) {
    const response = await axios.post(`api/playoff/tournament/${idTournament}/generate`).catch(error => alert(error))
    return response.data
}
