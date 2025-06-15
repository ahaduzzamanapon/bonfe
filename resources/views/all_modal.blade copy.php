{{-- exam result modal --}}
<!-- Modal -->
<div class="modal fade" id="exam_result_modal" tabindex="-1" role="dialog" aria-labelledby="exam_result_modalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Exam Result</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    onclick="$('#exam_result_modal').modal('hide')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <label for="ExamResult_field">Exam Result</label>
                        <select class="form-control" id="ExamResult_field">
                            <option value="Passed"> Competent </option>
                            <option value="Fail"> Non-Competent </option>
                        </select>
                    </div>
                    <div style="padding: 10px;">   
                        <label for="ExamResult_field">Competence</label>
                        <div id="competence_pass_div"  style="border: 1px solid;padding: 10px;">
                           
                        </div>
                    </div>
                    <script>
                        document.getElementById('ExamResult_field').addEventListener('change', function() {
                            if (this.value === 'Passed') {
                                document.getElementsByName('competence_ids[]').forEach(input => {
                                    input.checked = true;
                                })
                            } else {
                               document.getElementsByName('competence_ids[]').forEach(input => {
                                    input.checked = false;
                                })
                            }
                        });
                    </script>
                    <div class="form-group">
                        <label for="ExamResult_field">Exam Result Sheet</label>
                        <input type="file" class="form-control" id="ExamResultSheet_field"
                            accept=".pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx" name="ExamResultSheet_field" required>
                        <small class="form-text text-muted">Upload a file with the exam result. Accepted formats: .pdf,
                            .doc, .docx, .xls, .xlsx, .ppt, .pptx</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#exam_result_modal').modal('hide')"
                    data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submit_exam_result()">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script>
    function submit_exam_result() {
        const examResult = $('#ExamResult_field').val();
        const examResultSheet = $('#ExamResultSheet_field')[0].files[0];
        const allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
        const fileExtension = examResultSheet.name.split('.').pop().toLowerCase();

        if (!allowedExtensions.includes(fileExtension)) {
            alert('Invalid file type. Please upload a file with one of the following extensions: ' + allowedExtensions
                .join(', '));
            return false;
        }

        const studentId = localStorage.getItem('student_id_for_am_resultex   ;')}